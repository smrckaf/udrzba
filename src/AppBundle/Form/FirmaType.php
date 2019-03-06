<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FirmaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('nazev',TextType::class,['label' => 'Název',]);
        $builder->add('adresa',TextType::class,['label' => 'Adresa',]);
        $builder->add('web',TextType::class,['label' => 'Web',]);
        $builder->add('kontakt',TextType::class,['label' => 'Kontakt',]);
        $builder->add('ico',TextType::class,['label' => 'IČO',]);
        $builder->add('dic',TextType::class,['label' => 'DIČ',]);
        $builder->add('dodacilhuta',TextType::class,['label' => 'Dodací lhůta',]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Firma'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_firma';
    }


}
