<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Pripravek;
use AppBundle\Entity\Stroj;
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
 * @Route("/pripravek")
 */
class PripravekController extends Controller
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
     * @Route("/index/{stroj}", name="pripravek-index")
     */
    public function indexAction(Stroj $stroj)
    {
        $pripravky = $this->udrzbaManager->getPripravkyByStroj($stroj);
        return $this->render("pripravek/index.html.twig", [
            'pripravky' => $pripravky,
            'stroj' => $stroj,
        ]);
    }

    /**
     * @Route("/upravit/{pripravek}", name="pripravek-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Pripravek $pripravek = null)
    {
        $stroj = $this->em->find(Stroj::class, $request->query->getInt('stroj'));

        if ($pripravek === null)
            $pripravek = new Pripravek($stroj);
        $form = $formFactory->create(UpravitPripravkyType::class, $pripravek);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $this->udrzbaManager->ulozitPripravek($pripravek);
            $flashBag->add('success', 'Přípravek byl úspěšně přidán/upraven.');
            return $this->redirectToRoute('pripravek-index', ['stroj' => $stroj->getId()]);
        }
        return $this->render('pripravek/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{pripravek}", name="pripravek-smazat")
     */
    public function smazat(Pripravek $pripravek, FlashBagInterface $flashBag, Request $request)
    {
        $stroj = $this->em->find(Stroj::class, $request->query->getInt('stroj'));

        $this->udrzbaManager->smazatPripravek($pripravek);
        $flashBag->add('success', 'Přípravek byl úspěšně smazán.');

        return $this->redirectToRoute('pripravek-index', ['stroj' => $stroj->getId()]);
    }
}