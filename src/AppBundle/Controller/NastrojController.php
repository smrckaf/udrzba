<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Nastroj;
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
 * @Route("/nastroj")
 */
class NastrojController extends Controller
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
     * @Route("/index/{stroj}", name="nastroj-index")
     */
    public function indexAction(Stroj $stroj)
    {
        $nastroje = $this->udrzbaManager->getNastrojeByStroj($stroj);
        return $this->render("nastroj/index.html.twig", [
            'nastroje' => $nastroje,
            'stroj' => $stroj,
        ]);
    }

    /**
     * @Route("/upravit/{nastroj}", name="nastroj-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Nastroj $nastroj = null)
    {
        $stroj = $this->em->find(Stroj::class, $request->query->getInt('stroj'));

        if ($nastroj === null)
            $nastroj = new Nastroj($stroj);
        $form = $formFactory->create(UpravitPripravkyType::class, $nastroj);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $this->udrzbaManager->ulozitNastroj($nastroj);
            $flashBag->add('success', 'Nástroj byl úspěšně přidán/upraven.');
            return $this->redirectToRoute('nastroj-index', ['stroj' => $stroj->getId()]);
        }
        return $this->render('nastroj/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{nastroj}", name="nastroj-smazat")
     */
    public function smazat(Nastroj $nastroj, FlashBagInterface $flashBag, Request $request)
    {
        $stroj = $this->em->find(Stroj::class, $request->query->getInt('stroj'));

        $this->udrzbaManager->smazatNastroj($nastroj);
        $flashBag->add('success', 'Nástroj byl úspěšně smazán.');

        return $this->redirectToRoute('nastroj-index', ['stroj' => $stroj->getId()]);
    }
}