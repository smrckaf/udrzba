<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 18.11.2018
 * Time: 19:38
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Kompetence;
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

    /**
     * @return Pracovnik[]|array
     */
    public function getAllPracovnici()
    {
        return $this->em->getRepository(Pracovnik::class)->findAll();
    }

    public function getAllStroje()
    {
        return $this->em->getRepository(Stroj::class)->findAll();
    }

    public function ulozitStroj(Stroj $stroj)
    {
        if ($stroj->getId() === null)
            $this->em->persist($stroj);
        $this->em->flush();
    }

    public function smazatStroj(Stroj $stroj)
    {
        $this->em->remove($stroj);
        $this->em->flush();
    }

    public function ulozitPracovnika(Pracovnik $pracovnik)
    {
        if ($pracovnik->getId() === null)
            $this->em->persist($pracovnik);

        $this->em->flush();
    }

    public function smazatPracovnika(Pracovnik $pracovnik)
    {
        $this->em->remove($pracovnik);
        $this->em->flush();
    }

    public function getKompetencePracovnika(Pracovnik $pracovnik)
    {
        return $this->em->getRepository(Kompetence::class)->findBy(['pracovnik' => $pracovnik]);
    }
}