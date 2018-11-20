<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Kompetence
 *
 * @ORM\Table(name="kompetence")
 * @ORM\Entity()
 */
class Kompetence
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pracovnik", inversedBy="kompetence")
     * @ORM\Column(name="pracovnik", type="integer")
     */
    private $pracovnik;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Stroj")
     * @ORM\Column(name="stroj", type="integer")
     */
    private $stroj;

    /**
     * Kompetence constructor.*
     */
    public function __construct(Pracovnik $pracovnik, Stroj $stroj)
    {
        $this->pracovnik = $pracovnik;
        $this->stroj = $stroj;
    }

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
     * @return Kompetence
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
     * Set stroj
     *
     * @param string $stroj
     *
     * @return Kompetence
     */
    public function setStroj($stroj)
    {
        $this->stroj = $stroj;

        return $this;
    }

    /**
     * Get stroj
     *
     * @return string
     */
    public function getStroj()
    {
        return $this->stroj;
    }
}

