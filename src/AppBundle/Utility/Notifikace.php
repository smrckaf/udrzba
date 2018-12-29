<?php
/**
 * Created by PhpStorm.
 * User: smrcka
 * Date: 04.12.2018
 * Time: 10:55
 */

namespace AppBundle\Utility;
use RedjanYm\FCMBundle\FCMClient;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;





class Notifikace
{
    /**
     * @var FCMClient
     */
    private $FCMClient;

    public function __construct(ContainerInterface $container)
    {
        $this->FCMClient = $container->get('redjan_ym_fcm.client');
    }

    public function posliAction($title, $body, $zarizeni)
    {
        $notification = $this->FCMClient->createDeviceNotification(
            $title,
            $body,
            'Firebase Token of the device who will recive the notification'
        );

        //$notification->setDeviceToken('dqyYb6JFeLY:APA91bHSDOHolEUB-RddAXm5wKp1c6oRu1TVpBIX7mbCjJBH1Xv8bm_Z8yRIsuL3ra3cx7Am4NHBmjUc7jYukgEL5sWKEo-M7QC_Clr3FcjQ5Y5RuBtod6GBeLdmb4dGE08g3Ix3Q1EX');

        $notification->setDeviceToken($zarizeni);

        $this->FCMClient->sendNotification($notification);

    }
}