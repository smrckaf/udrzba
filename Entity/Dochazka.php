<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dochazka
 *
 * @ORM\Table(name="dochazka")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DochazkaRepository")
 */
class Dochazka
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="pracovnik", type="integer")
     */
    private $pracovnik;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prichod", type="datetime")
     */
    private $prichod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="odchod", type="datetime")
     */
    private $odchod;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pracovnik
     *
     * @param integer $pracovnik
     *
     * @return Dochazka
     */
    public function setPracovnik($pracovnik)
    {
        $this->pracovnik = $pracovnik;

        return $this;
    }

    /**
     * Get pracovnik
     *
     * @return int
     */
    public function getPracovnik()
    {
        return $this->pracovnik;
    }

    /**
     * Set prichod
     *
     * @param \DateTime $prichod
     *
     * @return Dochazka
     */
    public function setPrichod($prichod)
    {
        $this->prichod = $prichod;

        return $this;
    }

    /**
     * Get prichod
     *
     * @return \DateTime
     */
    public function getPrichod()
    {
        return $this->prichod;
    }

    /**
     * Set odchod
     *
     * @param \DateTime $odchod
     *
     * @return Dochazka
     */
    public function setOdchod($odchod)
    {
        $this->odchod = $odchod;

        return $this;
    }

    /**
     * Get odchod
     *
     * @return \DateTime
     */
    public function getOdchod()
    {
        return $this->odchod;
    }
}

