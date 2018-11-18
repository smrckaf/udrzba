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
 * @Route("/pracovnik")
 */
class PracovnikController extends Controller
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
     * @Route("/index", name="pracovnik-index")
     */
    public function indexAction()
    {
        $pracovnici = $this->udrzbaManager->getAllPracovnici();
        return $this->render("pracovnik/index.html.twig", [
            'pracovnici' => $pracovnici,
        ]);
    }
}