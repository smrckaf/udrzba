<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use AppBundle\Entity\Stroj;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UpravitPravidelnouUdrzbuType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idStroje', EntityType::class, [
            'class'     => Stroj::class,
            'choice_label' => 'nazev',
            'label'     => 'Stroj',
        ]);

        $builder->add('datumUdrzby', DateTimeType::class, [
            'label' => 'Plánované datum',
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
        ]);

        $builder->add('popisUdrzby', TextType::class, [
            'label' => 'Popis údržby',
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