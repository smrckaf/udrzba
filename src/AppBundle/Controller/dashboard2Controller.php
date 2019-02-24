<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 16:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Stroj;
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
     * dashboard2Controller constructor.
     * @param UdrzbaManager $udrzbaManager
     */
    public function __construct(UdrzbaManager $udrzbaManager)
    {
        $this->udrzbaManager = $udrzbaManager;
    }


    /**
     * @Route("/dashboard2", name="dashboard2")
     */
    public function indexAction()
    {
        return $this->render("udrzba/dashboard2.html.twig");
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function poruchaAction()
    {
        $a=$this->udrzbaManager->getPorucha();
        $n=$this->udrzbaManager->getNaprirazeneukoly();
        $p=$this->udrzbaManager->getPracovniciudrzby();
        $o=$this->udrzbaManager->getPocetotevrenychukolu();
        $u=$this->udrzbaManager->getPocetuzavrenychpripadu();
        $b=$this->udrzbaManager->getPocetpripadubeznotifikace();

        $s=$this->udrzbaManager->getSoucetcasu()/3600;

        return $this->render("udrzba/dashboard2.html.twig", ["a"=>$a,"n"=>$n, "p"=>$p,"o"=>$o, "u"=>$u, "b"=>$b,  "s"=>$s]  );
    }
}