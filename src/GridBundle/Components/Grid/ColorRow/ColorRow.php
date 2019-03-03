<?php

namespace GridBundle\Components\Grid\ColorRow;

/**
 * Třída reprezentující případné podbarvení řádku v gridu podle stavu nějakého sloupce z queryBuilderu
 * Class ColorRow
 * @package Ensis\Bundle\CmsCoreBundle\Components\Grid\ColorRow
 */
class ColorRow
{

    /**
     * @var string
     */
    private $sourceColumnName;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $cssColor;


    public function __construct(string $sourceColumnName, $value, string $cssColor)
    {
        $this->sourceColumnName = $sourceColumnName;
        $this->value = $value;
        $this->cssColor = $cssColor;
    }

    /**
     * @return string
     */
    public function getSourceColumnName() : string
    {
        return $this->sourceColumnName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function checkValue($value, $data)
    {
        if (is_callable($this->value)) {
            return call_user_func($this->value, $value, $data);
        }

        if ($value == $this->value) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCssColor() : string
    {
        return $this->cssColor;
    }



}