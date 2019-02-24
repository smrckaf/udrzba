<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use AppBundle\Entity\Lokace;
use AppBundle\Entity\Skupina;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UpravitStrojType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nazev', TextType::class, [
            'label' => 'Název stroje',
            'required' => true,
        ]);

        $builder->add('id1pcontrol', IntegerType::class, [
            'label' => 'ID (1P Control)',
            'required' => true,
            'attr' => [
                'min' => 1,
            ],
        ]);

        $builder->add('status', TextType::class, [
            'label' => 'Status stroje',
            'required' => true,
        ]);

        $builder->add('skupina', EntityType::class, [
            'class' => Skupina::class,
            'choice_label' => 'nazev',
            'label' => 'Skupina',
            'required' => false,
        ]);

        $builder->add('lokace', EntityType::class, [
            'class' => Lokace::class,
            'choice_label' => 'nazev',
            'label' => 'Lokace',
            'required' => false,
        ]);

        $builder->add('jeAktivni', CheckboxType::class, [
            'label' => 'Je aktivní',
            'required' => false,
        ]);

        $builder->add('odeslat', SubmitType::class, [
            'label' => 'Odeslat',
            'attr' => [
                'class' => 'btn btn-info'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

            ]
        );
    }
}