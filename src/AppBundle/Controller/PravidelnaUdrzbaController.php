<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Pravidelnaudrzba;
use AppBundle\Entity\Stroj;
use AppBundle\Form\PravidelnaUdrzbaFilterType;
use AppBundle\Form\UpravitPravidelnouUdrzbuType;
use AppBundle\Manager\UdrzbaManager;
use AppBundle\Form\UpravitStrojType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @Route("/pravidelna-udrzba")
 */
class PravidelnaUdrzbaController extends Controller
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
     * @Route("/index/{obdobi}", name="pravidelna-udrzba-index")
     */
    public function indexAction(FormFactoryInterface $formFactory, Request $request, $obdobi = 1)
    {
        $form = $formFactory->create(PravidelnaUdrzbaFilterType::class, ['obdobi' => $obdobi]);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            return $this->redirectToRoute('pravidelna-udrzba-index', ['obdobi' => $form->getData()['obdobi']]);
        }

        $pravidelneUdrzby = $this->udrzbaManager->getPravidelneUdrzby($obdobi);
        return $this->render("pravidelnaUdrzba/index.html.twig", [
            'pravidelneUdrzby' => $pravidelneUdrzby,
            'form' => $form->createView(),
            'obdobi' => $obdobi,
        ]);
    }

    /**
     * @Route("/upravit/{pravidelnaUdrzba}", name="pravidelna-udrzba-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Pravidelnaudrzba $pravidelnaUdrzba = null)
    {
        $obdobi = $request->query->getInt('obdobi');
        if ($pravidelnaUdrzba === null)
            $pravidelnaUdrzba = new Pravidelnaudrzba();
        $form = $formFactory->create(UpravitPravidelnouUdrzbuType::class, $pravidelnaUdrzba);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $this->udrzbaManager->ulozitPravidelnouUdrzbu($pravidelnaUdrzba);
            $flashBag->add('success', 'Plánovaná udržba byla úspěšně zadána/upravena.');
            return $this->redirectToRoute('pravidelna-udrzba-index', ['obdobi' => $obdobi]);
        }
        return $this->render('pravidelnaUdrzba/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{pravidelnaUdrzba}", name="pravidelna-udrzba-smazat")
     */
    public function smazat(Pravidelnaudrzba $pravidelnaUdrzba, FlashBagInterface $flashBag, Request $request)
    {
        $obdobi = $request->query->getInt('obdobi');
        $this->udrzbaManager->smazatPravidelnouUdrzbu($pravidelnaUdrzba);
        $flashBag->add('success', 'Plánovaná údržba byla úspěšně smazána.');

        return $this->redirectToRoute('pravidelna-udrzba-index', ['obdobi' => $obdobi]);
    }

    /**
     * @Route("/kalendar", name="pravidelna-udrzba-kalendar")
     */
    public function kalendar()
    {


        return $this->render("pravidelnaUdrzba/kalendar2.html.twig", [
            'vyberstroj' => 1,
        ]);
    }





}