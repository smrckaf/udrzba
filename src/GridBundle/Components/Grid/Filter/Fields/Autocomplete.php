<?php

namespace GridBundle\Components\Grid\Filter\Fields;

use AppBundle\Entity\Products\Product;
use Ensis\Bundle\CmsCoreBundle\FormType\AutocompleteType;

class Autocomplete extends Field
{
    const OPERATION_LIKE = 'LIKE';
    const OPERATION_EQUAL = '=';

    private $route;

    public function __construct(
        string $name,
        string $translatedName,
        string $tableAlias,
        string $columnName,
        string $route,
        string $defaultValue = null,
        string $operation = self::OPERATION_EQUAL
    ) {
        parent::__construct($name, $translatedName, $tableAlias, $columnName, AutocompleteType::class, $operation, $defaultValue);
        $this->route = $route;
    }

    public function getFormFieldOptions(): array
    {
        return [
            'label' => $this->getTranslatedName(),
            'class' => Product::class,
            'choice_label' => 'id',
            'route' => $this->route,
            'required' => false,
        ];
    }

}
