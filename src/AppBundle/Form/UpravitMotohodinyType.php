<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use AppBundle\Entity\Lokace;
use AppBundle\Entity\Pripravek;
use AppBundle\Entity\Skupina;
use AppBundle\Manager\UdrzbaManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UpravitMotohodinyType extends AbstractType
{
    private $router;
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;

    public function __construct(RouterInterface $router, UdrzbaManager $udrzbaManager)
    {
        $this->router = $router;
        $this->udrzbaManager = $udrzbaManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hodnota', IntegerType::class, [
            'label' => 'Celkem k aktuálnímu datu',
            'required' => true,
            'attr' => [
                'min' => $options['minHodnota'],
            ]
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
                'minHodnota' => 1,
            ]
        );
    }
}