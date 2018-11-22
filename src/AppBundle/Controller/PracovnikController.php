<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Pracovnik;
use AppBundle\Form\UpravitPracovnikaType;
use AppBundle\Manager\UdrzbaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/pracovnik")
 */
class PracovnikController extends Controller
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
     * @Route("/index", name="pracovnik-index")
     */
    public function indexAction()
    {
        $pracovnici = $this->udrzbaManager->getAllPracovnici();
        return $this->render("pracovnik/index.html.twig", [
            'pracovnici' => $pracovnici,
        ]);
    }

    /**
     * @Route("/upravit/{pracovnik}", name="pracovnik-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, UserPasswordEncoderInterface $encoder, Pracovnik $pracovnik = null)
    {
        if ($pracovnik === null)
            $pracovnik = new Pracovnik();
        $form = $formFactory->create(UpravitPracovnikaType::class, $pracovnik);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $pracovnik->setHeslo($encoder->encodePassword($pracovnik, $pracovnik->getHeslo()));
            $this->udrzbaManager->ulozitPracovnika($pracovnik);
            $flashBag->add('success', 'Pracovník byl úspěšně přidán/upraven.');
            return $this->redirectToRoute('pracovnik-index');
        }
        return $this->render('pracovnik/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{pracovnik}", name="pracovnik-smazat")
     */
    public function smazat(Pracovnik $pracovnik, FlashBagInterface $flashBag)
    {
        $this->udrzbaManager->smazatPracovnika($pracovnik);
        $flashBag->add('success', 'Pracovník byl úspěšně smazán.');

        return $this->redirectToRoute('pracovnik-index');
    }






}