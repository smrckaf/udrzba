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
     * @Route("/reporting", name="reporting")
     */
    public function indexAction()
    {
        return $this->render("udrzba/reporting.html.twig");
    }


}