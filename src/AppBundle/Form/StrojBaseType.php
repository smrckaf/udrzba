<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class StrojBaseType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vyrobce', TextType::class, [
            'label' => 'Výrobce',
            'required' => false,
        ]);

        $builder->add('typ', TextType::class, [
            'label' => 'Typ',
            'required' => false,
        ]);

        $builder->add('oznaceni', TextType::class, [
            'label' => 'Označení',
            'required' => true,
        ]);

        $builder->add('rokVyroby', IntegerType::class, [
            'label' => 'Rok výroby',
            'required' => false,
        ]);

        $builder->add('poznamka', TextType::class, [
            'label' => 'Poznámka',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'inherit_data' => true,
            ]
        );
    }
}