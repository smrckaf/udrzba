<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 16:22
 */

namespace AppBundle\Controller;


use AppBundle\Manager\UdrzbaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UdrzbaController extends Controller
{
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;

    /**
     * UdrzbaController constructor.
     */
    public function __construct(UdrzbaManager $udrzbaManager)
    {
        $this->udrzbaManager = $udrzbaManager;
    }

    /**
     * @Route("/", name="udrzba-index")
     */
    public function indexAction()
    {
        return $this->render("udrzba/index.html.twig");
    }


    /**
     * @Route("/dashboard", name="udrzba-dashboard")
     */
    public function dashboardAction()
    {
        return $this->render("udrzba/dashboard.html.twig", [
            'poruchy' => $this->udrzbaManager->getExampleDashboardPoruchy(),
            'neprirazene' => $this->udrzbaManager->getExampleDashboardNeprirazene(),
            'pracovnici' => $this->udrzbaManager->getExampleDashboardPracovnici(),
            'prehled' => $this->udrzbaManager->getExampleDashboardPrehled(),
        ]);
    }
}