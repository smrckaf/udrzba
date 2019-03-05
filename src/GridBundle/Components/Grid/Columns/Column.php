<?php

namespace GridBundle\Components\Grid\Columns;

class Column
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $sourceColumnName;

    /**
     * @var callable
     */
    private $formatting;

    /**
     * @var string
     */
    private $classes;

    /**
     * @var bool
     */
    protected $sortable;

    /**
     * @var string
     */
    private $tableAlias;

    /**
     * Column constructor.
     *
     * @param string        $name
     * @param string        $sourceColumnName
     * @param string        $tableAlias
     * @param callable|null $formatting
     */
    public function __construct(string $name, string $sourceColumnName, string $tableAlias, callable $formatting = null)
    {
        $this->name = $name;
        $this->sourceColumnName = $sourceColumnName;
        $this->tableAlias = $tableAlias;
        $this->formatting = $formatting;
        $this->setSortable(true);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSourceColumnName(): string
    {
        return $this->sourceColumnName;
    }

    /**
     * @return callable
     */
    public function getFormatting(): callable
    {
        if ($this->formatting === null) {
            return function ($data) {
                return $data === null ? '' : $data;
            };
        }

        return $this->formatting;
    }

    /**
     * @param $data
     * @param $allData
     *
     * @return string
     */
    public function doFormatting($data, $allData): string
    {
        return call_user_func($this->getFormatting(), $data, $allData);
    }

    /**
     * @return string
     */
    public function getClasses(): string
    {
        return $this->classes ?: '';
    }

    /**
     * @param string $classes
     * @return $this
     */
    public function setClasses(string $classes)
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableAlias(): string
    {
        return $this->tableAlias;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     * @return $this
     */
    public function setSortable(bool $sortable)
    {
        $this->sortable = $sortable;

        return $this;
    }
}
