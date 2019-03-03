<?php

namespace GridBundle\Components\Grid\Columns;

class DateTimeColumn extends Column
{
    const HUMAN_DATE = 'd.m.Y';

    const HUMAN_DATE_TIME = 'd.m.Y H:i';
    /**
     * @var string
     */
    private $dateTimeFormat;

    public function __construct(string $name, string $tableAlias, string $sourceColumnName, string $dateTimeFormat = self::HUMAN_DATE)
    {
        $this->dateTimeFormat = $dateTimeFormat;
        parent::__construct($name, $sourceColumnName, $tableAlias, $this->setFormating());
    }

    private function setFormating(): callable
    {
        return function ($dateTime) {
            if ($dateTime instanceof \DateTime) {
                return $dateTime->format($this->dateTimeFormat);
            }

            return '-';
        };
    }
}
