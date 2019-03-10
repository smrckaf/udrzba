<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Motohodiny;
use AppBundle\Entity\Pripravek;
use AppBundle\Entity\Stroj;
use AppBundle\Form\UpravitMotohodinyType;
use AppBundle\Form\UpravitPripravkyType;
use AppBundle\Manager\UdrzbaManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @Route("/motohodiny")
 */
class MotohodinyController extends Controller
{
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * PracovnikController constructor.
     */
    public function __construct(UdrzbaManager $udrzbaManager, EntityManagerInterface $em)
    {
        $this->udrzbaManager = $udrzbaManager;
        $this->em = $em;
    }

    /**
     * @Route("/index/{stroj}", name="motohodiny-index")
     */
    public function indexAction(Stroj $stroj)
    {
        $motohodiny = $this->udrzbaManager->getMotohodinyByStroj($stroj);
        return $this->render("motohodiny/index.html.twig", [
            'motohodiny' => $motohodiny,
            'stroj' => $stroj,
        ]);
    }

    /**
     * @Route("/upravit/{motohodiny}", name="motohodiny-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Motohodiny $motohodiny = null)
    {
        $stroj = $this->em->find(Stroj::class, $request->query->getInt('stroj'));

        if ($motohodiny === null)
            $motohodiny = new Motohodiny($stroj);

        $minHodnota = $this->udrzbaManager->getMinMotohodinyForForm($stroj);
        $form = $formFactory->create(UpravitMotohodinyType::class, $motohodiny, ['minHodnota' => $minHodnota]);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $this->udrzbaManager->ulozitMotohodiny($motohodiny);
            $flashBag->add('success', 'Motohodiny byly úspěšně přidány/upraveny.');
            return $this->redirectToRoute('motohodiny-index', ['stroj' => $stroj->getId()]);
        }
        return $this->render('motohodiny/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{motohodiny}", name="motohodiny-smazat")
     */
    public function smazat(Motohodiny $motohodiny, FlashBagInterface $flashBag, Request $request)
    {
        $stroj = $this->em->find(Stroj::class, $request->query->getInt('stroj'));

        $this->udrzbaManager->smazatMotohodiny($motohodiny);
        $flashBag->add('success', 'Motohodiny byly úspěšně smazány.');

        return $this->redirectToRoute('motohodiny-index', ['stroj' => $stroj->getId()]);
    }
}