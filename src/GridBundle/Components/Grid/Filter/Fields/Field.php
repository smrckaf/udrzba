<?php

namespace GridBundle\Components\Grid\Filter\Fields;

use Ensis\Bundle\CmsCoreBundle\Components\Grid\Exceptions\GridException;

/**
 * Class Field
 * @package Ensis\Bundle\CmsCoreBundle\Components\Grid\Filter\Fields
 */
abstract class Field
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $tableAlias;
    /**
     * @var string
     */
    private $columnName;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var string
     */
    private $formFieldType;
    /**
     * @var string
     */
    private $operation;
    /**
     * @var string
     */
    private $translatedName;

    private $formFieldOptions;

    public function __construct(string $name, string $translatedName, string $tableAlias, string $columnName, string $formFieldType, string $operation, $defaultValue = null, $formFieldOptions = [])
    {
        $this->name = $name;
        $this->tableAlias = $tableAlias;
        $this->columnName = $columnName;
        $this->setValue($defaultValue);
        $this->formFieldType = $formFieldType;
        $this->operation = $operation;
        $this->translatedName = $translatedName;
        $this->formFieldOptions = $formFieldOptions;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTableAlias() : string
    {
        return $this->tableAlias;
    }

    /**
     * @return string
     */
    public function getColumnName() : string
    {
        return $this->columnName;
    }

    /**
     * @return mixed
     */
    public function getValue(bool $prepareForQueryBuilder = false)
    {
        return $this->value;
    }

    public function getOverviewValue()
    {
        return $this->getValue();
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Celý název column ve formátu table_alias.column_name (např.: c.name)
     * @return string
     */
    public function getWholeColumnName()
    {
        return empty($this->getTableAlias()) ? $this->getColumnName() : $this->getTableAlias() . "." . $this->getColumnName();
    }

    /**
     * @return string
     */
    public function getFormFieldType() : string
    {
        return $this->formFieldType;
    }

    /**
     * @return string
     */
    public function getOperation() : string
    {
        return $this->operation;
    }

    /**
     * @return array
     */
    public function getFormFieldOptions() : array
    {
        return $this->formFieldOptions;
    }

    /**
     * @return string
     */
    public function getTranslatedName() : string
    {
        return $this->translatedName;
    }


}