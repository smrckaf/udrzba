<?php
/**
 * Created by PhpStorm.
 * User: smrcka
 * Date: 22.11.2018
 * Time: 10:59
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Porucha;


class PoruchaRest extends FOSRestController
{


    /**
     * @Rest\Get("/porucha")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Porucha')->findAll();
        if ($restresult === null) {
            return new View("there are no porucha exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }



}