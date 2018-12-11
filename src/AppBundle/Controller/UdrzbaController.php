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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utility;






class UdrzbaController extends Controller
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
     * UdrzbaController constructor.
     */
    public function __construct(UdrzbaManager $udrzbaManager, Utility\Notifikace $notifikace)
    {
        $this->udrzbaManager = $udrzbaManager;
        $this->notifikace = $notifikace;
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

    /**
     * @Route("/posli", name="posli")
     */
    //pokus, zda to posila nofikci -ok, pak z projektu odstranit  vytvoreno 4.12.2018
    public function posliAction(Request $request)
    {
        $fcmClient = $this->get('redjan_ym_fcm.client');

        $notification = $fcmClient->createDeviceNotification(
            'Title of Notification',
            'Body of Notification',
            'Firebase Token of the device who will recive the notification'
        );
        $notification->setDeviceToken('dqyYb6JFeLY:APA91bHSDOHolEUB-RddAXm5wKp1c6oRu1TVpBIX7mbCjJBH1Xv8bm_Z8yRIsuL3ra3cx7Am4NHBmjUc7jYukgEL5sWKEo-M7QC_Clr3FcjQ5Y5RuBtod6GBeLdmb4dGE08g3Ix3Q1EX');
        $fcmClient->sendNotification($notification);
        return new JsonResponse([]);

    }
    /**
     * @Route("/posli2", name="posli2")
     */
    //pokus, zda to posila nofikci -ok, pak z projektu odstranit  vytvoreno 4.12.2018
    public function posli2Action(Request $request)
    {
        $this->notifikace->posliAction('title', 'body');
        return new JsonResponse([]);

    }




}