<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Porucha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Porucha controller.
 *
 * @Route("vypisporuch")
 */
class PoruchaController extends Controller
{
    /**
     * Lists all porucha entities.
     *
     * @Route("/", name="vypisporuch_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $poruchas = $em->getRepository('AppBundle:Porucha')->findAll();

        return $this->render('porucha/index.html.twig', array(
            'poruchas' => $poruchas,
        ));
    }

    /**
     * Finds and displays a porucha entity.
     *
     * @Route("/{id}", name="vypisporuch_show")
     * @Method("GET")
     */
    public function showAction(Porucha $porucha)
    {

        return $this->render('porucha/show.html.twig', array(
            'porucha' => $porucha,
        ));
    }
}
