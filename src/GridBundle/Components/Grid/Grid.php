<?php

namespace GridBundle\Components\Grid;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use GridBundle\Components\Grid\ColorRow\ColorRow;
use GridBundle\Components\Grid\Columns\Column;
use GridBundle\Components\Grid\Exceptions\GridException;
use GridBundle\Components\Grid\Filter\Fields\Hidden;
use GridBundle\Components\Grid\Filter\Filter;
use GridBundle\Components\Grid\Paginator\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Třída s informacemi pro service, která vykreslí přehled.
 */
class Grid
{
    /**
     * @var Column[]
     */
    private $columns = [];

    /**
     * @var Button[]
     */
    private $buttons = [];

    /**
     * @var PaginationInterface
     */
    private $pagination;

    private $footer;

//    /**
//     * @var array
//     */
//    private $renderData;

    /**
     * @var ColorRow
     */
    private $colorRows = [];

    /** @var int Počet sloupečků ve výpisu - důležitá metadata */
    private $inLineColumnsCount = 0;

    /** @var Filter */
    private $filter;

    /** @var array */
    private $filterOverview = [];

    /** @var bool  */
    private $hidePaginator = false;

    /**
     * Grid constructor.
     *
     * @param $dataProvider
     * @param Paginator $paginator
     * @param Filter|null $filter
     * @param PropertyAccessor|null $propertyAccessor
     */
    public function __construct($dataProvider, Paginator $paginator, Filter $filter = null, PropertyAccessor $propertyAccessor = null)
    {
        if (!($dataProvider instanceof QueryBuilder || is_array($dataProvider) || $dataProvider instanceof Collection)) {
            throw new IncompatibleDataProviderException('Incompatible data provider!');
        }

        if (null != $filter) {
            $this->filter = $filter;
            $dataProvider = $this->applyFilter($dataProvider, $filter, $propertyAccessor);
        }

        $this->setPagination($paginator, $dataProvider);
    }

    private function applyFilter($dataProvider, Filter $filter, PropertyAccessor $propertyAccessor = null)
    {
        $filter->handleRequest();

        $this->filterOverview = [];
        foreach ($filter->getFields() as $field) {
            if ($field instanceof Hidden) {
                continue;
            }
            if (!is_null($field->getValue()) && $field->getValue() != '') {
                if ($dataProvider instanceof QueryBuilder) {
                    $dataProvider->andWhere($field->getWholeColumnName() . ' ' . $field->getOperation() . ' :' . $field->getName() . 'Value')
                        ->setParameter($field->getName() . 'Value', $field->getValue(true));
                } else if (is_array($dataProvider)) {
                    foreach ($dataProvider as $key => $item) {
                        $data = $propertyAccessor->getValue($item, $field->getColumnName());
                        if ($field->getOperation() == '=') {
                            if ($data != $field->getValue()) {
                                unset($dataProvider[$key]);
                            }
                        } else if (strpos(strtolower($data), strtolower($field->getValue())) === false) {
                            unset($dataProvider[$key]);
                        }
                    }
                    $dataProvider = array_values($dataProvider);
                }

                // vytvoříme overview
                $this->filterOverview[$field->getTranslatedName()] = $field->getOverviewValue();
            }
        }

        return $dataProvider;
    }

    private function setPagination(Paginator $paginator, $dataProvider)
    {
        // pokud bude paginator bez stránkování, nastaví se vysoký limit
        if ($paginator->getLimit() == Paginator::UNLIMITED) {
            $limit = 10000;
            $this->hidePaginator = true;
        } else {
            $limit = $paginator->getLimit() > 0 ? $paginator->getLimit() : 10;
        }

        $this->pagination = $paginator->getKnpPaginator()->paginate(
            $dataProvider,
            $paginator->getPage(),
            $limit
        );

        if ($this->pagination->getCurrentPageNumber() > 1 &&
            ($this->pagination->getTotalItemCount() < (($this->pagination->getCurrentPageNumber() - 1) * $this->pagination->getItemNumberPerPage()))) {
            $this->pagination = $paginator->getKnpPaginator()->paginate(
                $dataProvider,
                1,
                $limit
            );
        }
    }

    /**
     * Přidá sloupeček do gridu.
     *
     * @param Column $column
     *
     * @return $this
     */
    public function addColumn(Column $column)
    {
        $this->columns[count($this->columns)] = $column;
        ksort($this->columns);

        return $this;
    }

    /**
     * Přidá tlačítko do gridu.
     *
     * @param Button $button
     *
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->buttons[$button->getType()] = $button;

        return $this;
    }

    /**
     * Připraví data pro vypsání v gridu
     * TODO - tady přidat omezení z filtru a paginatoru.
     */
    public function prepareRender()
    {
        // musí obsahovat alespoň jeden Column
        if (!count($this->columns)) {
            throw new GridException('Grid required at least one Column!');
        }

        $headButtonsCount = 0; // TODO - pořešit větší počet tlačítek v head než v line
        $inLineButtonsCount = 0;
        foreach ($this->getButtons() as $button) {
            if ($button->getType() == Button::BTN_ADD) {
                ++$headButtonsCount;
            } else {
                ++$inLineButtonsCount;
            }
        }
        $inLineButtonsCount = $inLineButtonsCount > 0 ? $inLineButtonsCount : $headButtonsCount;
        $this->inLineColumnsCount = count($this->columns) + $inLineButtonsCount;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return Button[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @return PaginationInterface
     */
    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }

    /**
     * @return int
     */
    public function getInLineColumnsCount(): int
    {
        return $this->inLineColumnsCount;
    }

    /**
     * Nastaví barvu řádku v gridu, pokud bude mít definovaný sloupec z queryBuilderu požadovanou hodnotu.
     *
     * @param string $sourceColumnName
     * @param $value
     * @param string $cssColor
     */
    public function setColorRow(string $sourceColumnName, $value, string $cssColor)
    {
        $this->colorRows[] = new ColorRow($sourceColumnName, $value, $cssColor);
    }

    /**
     * Má grid podbarvovat řádky podle stavu nějakých sloupců?
     *
     * @return bool
     */
    public function hasColorRows()
    {
        return count($this->colorRows) > 0 ? true : false;
    }

    /**
     * @return mixed
     */
    public function getColorRows()
    {
        return $this->colorRows;
    }

    public function getFilter(): ?Filter
    {
        return $this->filter;
    }

    /**
     * @return bool
     */
    public function isHidePaginator() : bool
    {
        return $this->hidePaginator;
    }

    /**
     * @return array
     */
    public function getFilterOverview() : array
    {
        return $this->filterOverview;
    }

    /**
     * @return mixed
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param mixed $footer
     * @return Grid
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
        return $this;
    }
}
