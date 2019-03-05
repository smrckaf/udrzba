<?php

namespace GridBundle\Components\Grid\Columns;

class TextColumn extends Column
{
    public function __construct(string $name, string $tableAlias, string $sourceColumnName)
    {
        parent::__construct($name, $sourceColumnName, $tableAlias, $this->setFormating());
    }

    private function setFormating(): callable
    {
        return function ($text) {
            return (string) $text;
        };
    }
}
