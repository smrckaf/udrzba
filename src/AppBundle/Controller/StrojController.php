<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Manager\UdrzbaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/stroj")
 */
class StrojController extends Controller
{
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;

    /**
     * PracovnikController constructor.
     */
    public function __construct(UdrzbaManager $udrzbaManager)
    {
        $this->udrzbaManager = $udrzbaManager;
    }

    /**
     * @Route("/index", name="stroj-index")
     */
    public function indexAction()
    {
        $stroje = $this->udrzbaManager->getAllStroje();
        return $this->render("stroj/index.html.twig", [
            'stroje' => $stroje,
        ]);
    }
}