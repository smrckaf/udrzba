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






class dashboard2Controller extends Controller
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
     * @Route("/dashboard2", name="dashboard2")
     */
    public function indexAction()
    {
        return $this->render("udrzba/dashboard2.html.twig");
    }


}