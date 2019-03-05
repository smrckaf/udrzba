<?php
/**
 * Created by PhpStorm.
 * User: lubos
 * Date: 29.6.17
 * Time: 15:11.
 */

namespace GridBundle\Components\Grid\Filter\Fields;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class Date extends Field
{
    const OPERATION_EQUAL = '=';
    const OPERATION_LESS_THAN = '<';
    const OPERATION_GREATER_THAN = '>';
    const OPERATION_EQUAL_LESS_THAN = '<=';
    const OPERATION_EQUAL_GREATER_THAN = '>=';

    public function __construct(
        string $name,
        string $translatedName,
        string $tableAlias,
        string $columnName,
        \DateTime $defaultValue = null,
        string $operation = self::OPERATION_EQUAL
    ) {
        parent::__construct($name, $translatedName, $tableAlias, $columnName, DateType::class, $operation, $defaultValue);
    }

    public function getFormFieldOptions(): array
    {
        return [
            'data' => $this->getValue(),
            'label' => $this->getTranslatedName(),
            'format' => 'dd.MM.yyyy',
            'widget' => 'single_text',
            'required' => false,
        ];
    }

    public function setValue($value)
    {
        if (!($value instanceof \DateTime)) {
            try {
                $date = \DateTime::createFromFormat('d.m.Y', $value);
                if ($date instanceof \DateTime) {
                    parent::setValue($date);
                } else {
                    parent::setValue(null);
                }
            } catch (\Exception $ex) {
                parent::setValue(null);
            }
        }
    }

    public function getValue(bool $prepareForQueryBuilder = false)
    {
        if ($prepareForQueryBuilder) {
            $date = parent::getValue();
            if ($date instanceof \DateTime) {
                return $date->format('Y-m-d');
            }
        }

        return parent::getValue();
    }

    public function getOverviewValue()
    {
        $date = parent::getOverviewValue();
        return $date->format('d.m.Y');
    }
}
