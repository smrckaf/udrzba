<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LogNotifikace;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Lognotifikace controller.
 *
 * @Route("lognotifikace")
 */
class LogNotifikaceController extends Controller
{
    /**
     * Lists all logNotifikace entities.
     *
     * @Route("/", name="lognotifikace_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $logNotifikaces = $em->getRepository('AppBundle:LogNotifikace')->findAll();

        return $this->render('lognotifikace/index.html.twig', array(
            'logNotifikaces' => $logNotifikaces,
        ));
    }

    /**
     * Finds and displays a logNotifikace entity.
     *
     * @Route("/{id}", name="lognotifikace_show")
     * @Method("GET")
     */
    public function showAction(LogNotifikace $logNotifikace)
    {

        return $this->render('lognotifikace/show.html.twig', array(
            'logNotifikace' => $logNotifikace,
        ));
    }
}
