<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use AppBundle\Entity\KmenovaData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UpravitKmenovaDataType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vyrobniCislo', TextType::class, [
            'label' => 'Výrobní číslo',
            'required' => true,
        ]);

        $builder->add('stredisko', TextType::class, [
            'label' => 'Středisko',
            'required' => false,
        ]);

        $builder->add('uvedenoDoProvozu', DateType::class, [
            'label' => 'Uvedeno do provozu',
            'required' => false,
            'widget' => 'single_text',
        ]);

        $builder->add('zarukaDo', DateType::class, [
            'label' => 'Záruka do',
            'required' => false,
            'widget' => 'single_text',
        ]);

        $builder->add('obrazek1', FileType::class, [
            'label' => 'Obrázek 1',
            'required' => false,
        ]);

        $builder->add('obrazek2', FileType::class, [
            'label' => 'Obrázek 2',
            'required' => false,
        ]);

        $builder->add('obrazek3', FileType::class, [
            'label' => 'Obrázek 3',
            'required' => false,
        ]);

        $builder->add('strojBase', StrojBaseType::class, [
            'data_class' => KmenovaData::class,
            'label' => 'Základní údaje',
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