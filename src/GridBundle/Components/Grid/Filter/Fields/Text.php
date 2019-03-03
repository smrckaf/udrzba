<?php
/**
 * Created by PhpStorm.
 * User: lubos
 * Date: 29.6.17
 * Time: 15:11.
 */

namespace GridBundle\Components\Grid\Filter\Fields;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class Text extends Field
{
    const OPERATION_LIKE = 'LIKE';
    const OPERATION_EQUAL = '=';

    public function __construct(
        string $name,
        string $translatedName,
        string $tableAlias,
        string $columnName,
        string $defaultValue = null,
        string $operation = self::OPERATION_LIKE
    ) {
        parent::__construct($name, $translatedName, $tableAlias, $columnName, TextType::class, $operation, $defaultValue);
    }


    public function getFormFieldOptions() :array
    {
        return [
            'data' => $this->getValue(),
            'label' => $this->getTranslatedName(),
            'required' => false
        ];
    }

    public function getValue(bool $prepareForQueryBuilder = false)
    {
        if ($prepareForQueryBuilder) {
            if ($this->getOperation() == self::OPERATION_LIKE) {
                return '%'. parent::getValue() . '%';
            }
        } else {
            return parent::getValue();
        }
    }
}
