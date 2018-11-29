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
use AppBundle\Entity\Pracovnik;


class PracovnikRest extends FOSRestController
{


    /**
     * @Rest\Get("/user2")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Pracovnik')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }
    /**
     * @Rest\Get("/user/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Pracovnik')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }
    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateAction($id,Request $request)
    {
        $data = new Pracovnik();
        $name = $request->get('idzarizeni');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Pracovnik')->find($id);

        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($idzarizeni)) {
            $user->setIdzarizeni($idzarizeni);
            $sn->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        }

        else return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

}