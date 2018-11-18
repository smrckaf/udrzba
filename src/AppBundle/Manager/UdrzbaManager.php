<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 18.11.2018
 * Time: 19:38
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Pracovnik;
use AppBundle\Entity\Stroj;
use Doctrine\ORM\EntityManagerInterface;

class UdrzbaManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UdrzbaManager constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAllPracovnici()
    {
        return $this->em->getRepository(Pracovnik::class)->findAll();
    }

    public function getAllStroje()
    {
        return $this->em->getRepository(Stroj::class)->findAll();
    }
}