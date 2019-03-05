<?php
/**
 * Created by PhpStorm.
 * User: lubos
 * Date: 29.6.17
 * Time: 15:11.
 */

namespace GridBundle\Components\Grid\Filter\Fields;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Select extends Field
{
    const OPERATION_LIKE = 'LIKE';
    const OPERATION_EQUAL = '=';

    /**
     * @var array
     */
    private $choices = [];

    public function __construct(
        string $name,
        string $translatedName,
        string $tableAlias,
        string $columnName,
        array $choices,
        string $defaultValue = null,
        string $operation = self::OPERATION_EQUAL
    ) {
        parent::__construct($name, $translatedName, $tableAlias, $columnName, ChoiceType::class, $operation, $defaultValue);
        $this->choices = $choices;
    }

    public function getFormFieldOptions(): array
    {
        return [
            'choices' => $this->choices,
            'label' => $this->getTranslatedName(),
            'placeholder' => '--  Vše  --',
            'data' => $this->getValue(),
            'required' => false,
        ];
    }

}
