<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UpravitNahradniDilType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oznaceni', TextType::class, [
            'label' => 'Tovární označení',
            'required' => false,
        ]);

        $builder->add('nazev', TextType::class, [
            'label' => 'Název',
            'required' => true,
        ]);

        $builder->add('vyrobce', TextType::class, [
            'label' => 'Výrobce',
            'required' => false,
        ]);

        $builder->add('pocetKusu', IntegerType::class, [
            'label' => 'Počet kusů',
            'required' => false,
            'attr' => [
                'min' => 1,
            ]
        ]);

        $builder->add('cenaZaKusBezDPH', IntegerType::class, [
            'label' => 'Cena za kus (bez DPH)',
            'required' => false,
            'attr' => [
                'min' => 1,
            ]
        ]);

        $builder->add('zivotnost', IntegerType::class, [
            'label' => 'Životnost (počet hodin)',
            'required' => false,
            'attr' => [
                'min' => 1,
            ]
        ]);

        $builder->add('kontrola', IntegerType::class, [
            'label' => 'Pravidelná kontrola (počet hodin)',
            'required' => false,
            'attr' => [
                'min' => 1,
            ]
        ]);

        $builder->add('dokument', FileType::class, [
            'label' => 'Dokument',
            'required' => false,
        ]);

        $builder->add('dodavatel', TextType::class, [
            'label' => 'Dodavatel',
            'required' => false,
        ]);

        $builder->add('kontaktNaDodavatele', TextType::class, [
            'label' => 'Kontakt na dodavatele',
            'required' => false,
        ]);

        $builder->add('web', TextType::class, [
            'label' => 'Web',
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