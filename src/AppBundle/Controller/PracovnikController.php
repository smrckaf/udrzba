<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Pracovnik;
use AppBundle\Form\UpravitPracovnikaType;
use AppBundle\Manager\UdrzbaManager;
use GridBundle\Components\Grid\Columns\Column;
use GridBundle\Components\Grid\Filter\Filter;
use GridBundle\Components\Grid\Grid;
use GridBundle\Components\Grid\Paginator\Paginator;
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
use GridBundle\Components\Grid\Filter\Fields;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        if ($form->isValid() && $form->isSubmitted()) {
            $pracovnik->setHeslo($encoder->encodePassword($pracovnik, $pracovnik->getHeslo()));
            $this->udrzbaManager->ulozitPracovnika($pracovnik);
            $flashBag->add('success', 'Pracovník byl úspěšně přidán/upraven.');
            return $this->redirectToRoute('pracovnik-index');
        }
        return $this->render('pracovnik/upravit.html.twig', [
            'form' => $form->createView(),
            'skupiny' => $this->udrzbaManager->getNazvySkupin(),
        ]);
    }

    /**
     * @Route("/kalendar/{pracovnik}", name="pracovnik-kalendar")
     */
    public function kalendar(Pracovnik $pracovnik = null)
    {

        return $this->render('pracovnik/kalendar.html.twig', [
            'pracovnik' => $pracovnik,
            'skupiny' => $this->udrzbaManager->getNazvySkupin(),
        ]);
    }

    /**
     * @Route("/kalendar-data-ajax/{pracovnik}", name="pracovnik-kalendar-ajax")
     */
    public function kalendarDataAjax(Pracovnik $pracovnik = null)
    {
        $udrzbaStroje = $this->udrzbaManager->getPravidelneUdrzbyByPracovnik($pracovnik);


        $data = array();

        foreach ($udrzbaStroje as $udrzba) {
            $data[] = [
                "id" =>$udrzba["id"],
                "title" =>$udrzba["nazev"]." od ".$udrzba["datumUdrzbyod"]->format('H:i'),
                "start" =>$udrzba["datumUdrzbyod"]->format('Y-m-d')."T".$udrzba["datumUdrzbyod"]->format('H:i:s'),
                "end" =>$udrzba["datumUdrzbydo"]->format('Y-m-d')."T".$udrzba["datumUdrzbyod"]->format('H:i:s'),
                "url" =>"/pravidelna-udrzba/upravit/".$udrzba["id"]."?obdobi=3"];
        }

        return new JsonResponse($data);
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

    /**
     * @Route("/vypis", name="pracovnik-vypis")
     */
    // zde je pokusny vypis z GridBundle
    public function vypis(Request $dotaz)
    {
        return $this->render('pracovnik/vypis.html.twig', [
            'grid' => $this->createGrid($dotaz),
        ]);
    }

    private function createGrid(Request $request)
    {
        $paginator = new Paginator(
            $this->get('knp_paginator'),
            $request->query->getInt('page', $request->query->get('page', 1)),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        $filter = new Filter('Products', $this->container, $request);
        $filter->addField(new Fields\Text('jmeno', 'Jmeno', 'p', 'jmeno'));
        $filter->addField(new Fields\Select('jmeno', 'Jmeno', 'p', 'jmeno', [
            'Jmeno - Petr' => 'Petr',
            'Jmeno - Jan' => 'Jan',
        ]));

        $grid = new Grid(
            $this->getDoctrine()->getRepository(Pracovnik::class)->createQueryBuilder('p'),
            $paginator,
            $filter
        );

        $grid->addColumn(new Column('ID', 'id', 'p'));
//        $grid->addColumn(new Column('products.grid.category', 'cat_name', 'c'));
        $grid->addColumn(new Column('Jmeno', 'jmeno', 'p'));
        $grid->addColumn(new Column('Cele Jmeno', 'id', 'p', function ($id, Pracovnik $pracovnik) {
            return $pracovnik->getJmeno() . ' ' . $pracovnik->getPrijmeni();
        }));

//        $grid->addButton(new Button('add', Button::BTN_EDIT, 'homepage_edit'));
//        $grid->addButton(new Button('add', Button::BTN_ADD, 'homepage'));

        $grid->prepareRender();

        return $grid;
    }


}