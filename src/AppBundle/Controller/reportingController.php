<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 16:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\LogObsluhy;
use AppBundle\Entity\Pracovnik;
use AppBundle\Entity\Stroj;
use AppBundle\Manager\UdrzbaManager;
use GridBundle\Components\Grid\Button;
use GridBundle\Components\Grid\Columns\Column;
use GridBundle\Components\Grid\Columns\DateTimeColumn;
use GridBundle\Components\Grid\Filter\Filter;
use GridBundle\Components\Grid\Grid;
use GridBundle\Components\Grid\Paginator\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utility;
Use GridBundle\Components\Grid\Filter\Fields;






class reportingController extends Controller
{
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;
    /**
     * @var Utility\Notifikace
     */
    private $notifikace;



    /**
     * @Route("/reporting2", name="reporting2")
     */
    public function indexAction()
    {
        return $this->render("udrzba/reporting.html.twig");
    }

    /**
     * @Route("/reporting", name="reporting")
     */
    // zde je pokusny vypis z GridBundle
    public function vypis(Request $dotaz)
    {
        return $this->render('reporting/vypis.html.twig', [
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

        $filter = new Filter('Filtr', $this->container, $request);
        $pracovnici = [];
        $prac = $this->getDoctrine()->getRepository(Pracovnik::class)->findBy([], ['prijmeni' => 'ASC']);
        foreach($prac as $p){
            $pracovnici[$p->getPrijmeni()] = $p->getId();
        }
        $filter->addField(new Fields\Select('prijmeni', 'Příjmeni', 'prac', 'id', $pracovnici));

        $stroje = [];
        $str = $this->getDoctrine()->getRepository(Stroj::class)->findBy([], ['nazev' => 'ASC']);
        foreach($str as $p){
            $stroje[$p->getNazev()] = $p->getId();
        }
        $filter->addField(new Fields\Date('od', 'datuod', 'l', 'start',Null ,Fields\Date::OPERATION_EQUAL_GREATER_THAN));
        $filter->addField(new Fields\Date('do',  'datudo', 'l', 'start',Null ,Fields\Date::OPERATION_EQUAL_LESS_THAN));



        //$filter->addField(new Fields\Date('od', 'Od', 'l', 'start'));


        $log = $this->getDoctrine()->getManager()
            ->createQueryBuilder('l')
            ->from(LogObsluhy::class, 'l')
            ->addSelect("l.id, s.nazev stroj, prac.prijmeni pracovnik, l.start, l.konec")
            ->join('l.prevzal', 'prev')
            ->join('prev.idPoruchy', 'porucha')
            ->join('porucha.stroj', 's')
            ->join('prev.idPracovnika', 'prac')
            ->getQuery()
            ->getArrayResult();

        $grid = new Grid(
            $log,
            $paginator,
            $filter
        );
         $soucet=0;
        $grid->addColumn(new Column('ID', 'id', 'l',function($id) use (&$soucet)
        {
            $soucet++;
        return $id;
        } ));

        $grid->addColumn(new Column('Pracovník', 'pracovnik', 'l'));
        $grid->addColumn(new Column('Stroj', 'stroj', 'l'));
        /*$grid->addColumn(new Column('Datum poruchy', 'porucha', 'l', function ($id, LogObsluhy $logObsluhy) {
            return $logObsluhy->getIdprevzal()->getIdPoruchy()->getCasVzniku()->format('d.m.Y');
        }));*/
//        $grid->addColumn(new Column('products.grid.category', 'cat_name', 'c'));
        $grid->addColumn(new DateTimeColumn('Start', 'l', 'start'));
        $grid->addColumn(new DateTimeColumn('Konec', 'l', 'konec'));
        //$grid->setFooter(['id'=>20]);
        $grid->setFooter(['id'=>$soucet]);
        //soucty dole


       // $grid->addButton(new Button('add', Button::BTN_EDIT, 'homepage_edit'));
        //udelat routu pro detail bude mit parametr id
//        $grid->addButton(new Button('add', Button::BTN_ADD, 'homepage'));

        $grid->prepareRender();

        return $grid;
    }
}