<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 18.11.2018
 * Time: 16:22
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UdrzbaController extends Controller
{
    /**
     * @Route("/", name="udrzba-index")
     */
    public function indexAction()
    {
        return $this->render("udrzba/index.html.twig");
    }
}