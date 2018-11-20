<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Stroj;
use AppBundle\Manager\UdrzbaManager;
use AppBundle\Form\UpravitStrojType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @Route("/stroj")
 */
class StrojController extends Controller
{
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;

    /**
     * PracovnikController constructor.
     */
    public function __construct(UdrzbaManager $udrzbaManager)
    {
        $this->udrzbaManager = $udrzbaManager;
    }

    /**
     * @Route("/index", name="stroj-index")
     */
    public function indexAction()
    {
        $stroje = $this->udrzbaManager->getAllStroje();
        return $this->render("stroj/index.html.twig", [
            'stroje' => $stroje,
        ]);
    }

    /**
     * @Route("/upravit/{stroj}", name="stroj-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Stroj $stroj = null)
    {
        if ($stroj === null)
            $stroj = new Stroj();
        $form = $formFactory->create(UpravitStrojType::class, $stroj);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $this->udrzbaManager->ulozitStroj($stroj);
            $flashBag->add('success', 'Stroj byl úspěšně přidán/upraven.');
            return $this->redirectToRoute('stroj-index');
        }
        return $this->render('stroj/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{stroj}", name="stroj-smazat")
     */
    public function smazat(Stroj $stroj, FlashBagInterface $flashBag)
    {
        $this->udrzbaManager->smazatStroj($stroj);
        $flashBag->add('success', 'Stroj byl úspěšně smazán.');

        return $this->redirectToRoute('stroj-index');
    }
}