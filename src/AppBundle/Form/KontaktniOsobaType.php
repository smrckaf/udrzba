<?php

namespace AppBundle\Form;

use AppBundle\Entity\Firma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class KontaktniOsobaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('jmeno',TextType::class,['label' => 'Jméno',]);
        $builder->add('prijmeni',TextType::class,['label' => 'Příjmení',]);
        $builder->add('pozice',TextType::class,['label' => 'Pozice',]);
        $builder->add('email',TextType::class,['label' => 'Email',]);
        $builder->add('telefon',TextType::class,['label' => 'Telefon',]);
        $builder->add('poznamka',TextType::class,['label' => 'Poznámka',]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\KontaktniOsoba'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_kontaktniosoba';
    }


}
