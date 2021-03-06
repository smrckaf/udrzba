<?php
/**
 * Created by PhpStorm.
 * Pracovnik: hruska07
 * Date: 02.07.2018
 * Time: 10:41
 */

namespace AppBundle\Form;


use AppBundle\Entity\Kompetence;
use AppBundle\Entity\Stroj;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UpravitPracovnikaType extends AbstractType
{
    private $router;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('jmeno', TextType::class, [
            'label' => 'Jméno',
            'required' => true,
        ]);

        $builder->add('prijmeni', TextType::class, [
            'label' => 'Příjmení',
            'required' => true,
        ]);
        $builder->add('login', TextType::class, [
            'label' => 'Login',
            'required' => true,
        ]);
        $builder->add('heslo', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'Hesla se musí shodovat.',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label' => 'Heslo'),
            'second_options' => array('label' => 'Heslo znovu'),
        ));

        $builder->add('hodsazba', NumberType::class, [
            'label' => 'Hodinová sazba',
            'required' => true,
        ]);

        $builder->add('email', EmailType::class, [
            'label' => 'E-mail',
            'required' => false,
        ]);

        $builder->add('smennost', ChoiceType::class, [
            'label' => 'Směnnost',
            'required' => false,
            'placeholder' => false,
            'choices' => [
                'Ano' => true,
                'Ne' => false,
            ],
            'expanded' => true,
        ]);

        $builder->add('kvalifikace', TextType::class, [
            'label' => 'Kvalifikace',
            'required' => false,
        ]);

        $builder->add('poznamka', TextType::class, [
            'label' => 'Poznámka',
            'required' => false,
        ]);

        $builder->add('role', ChoiceType::class, [
            'label' => 'Role',
            'required' => true,
            'choices' => [
                'pracovník' => 'PRACOVNIK',
                'mistr' => 'MISTR',
                'vedoucí' => 'VEDOUCI',
                'admin' => 'ADMIN',
                'prohlizec_dashboardu' => 'DASHBOARD',
            ],
        ]);

        $builder->add('token', TextType::class, [
            'label' => 'Token',
            'required' => true,
        ]);
        $builder->add('idzarizeni', TextType::class, [
            'label' => 'ID zařízení',
            'required' => false,
            'attr'=>['readonly'=>true]
        ]);

        $builder->add('stroje', EntityType::class, [
            'class'     => Stroj::class,
            'query_builder'     => function(EntityRepository $er){
                return $er->createQueryBuilder("s")
                    ->leftJoin("s.skupina", "sk")
                    ->orderBy("sk.id", "ASC");
            },
            'choice_label' => 'nazev',
            'label'     => 'Kompetence (stroje)',
            'expanded'  => true,
            'multiple'  => true,
            'choice_attr'  => function($choiceValue, $key, $value){
                $skupina = $choiceValue->getSkupina();
                if($skupina === null){
                    return ['data-skupina' => 'Mimo skupinu'];
                }
                return ['data-skupina' => $skupina->getNazev()];
            },
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