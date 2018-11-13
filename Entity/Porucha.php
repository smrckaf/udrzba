<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Porucha
 *
 * @ORM\Table(name="porucha")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PoruchaRepository")
 */
class Porucha
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
     * @ORM\Column(name="stroj", type="integer")
     */
    private $stroj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="casvzniku", type="datetime")
     */
    private $casvzniku;

    /**
     * @var string
     *
     * @ORM\Column(name="oblastpriciny", type="string", length=255)
     */
    private $oblastpriciny;

    /**
     * @var int
     *
     * @ORM\Column(name="priorita", type="integer")
     */
    private $priorita;

    /**
     * @var string
     *
     * @ORM\Column(name="poznamka", type="string", length=255)
     */
    private $poznamka;


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
     * Set stroj
     *
     * @param integer $stroj
     *
     * @return Porucha
     */
    public function setStroj($stroj)
    {
        $this->stroj = $stroj;

        return $this;
    }

    /**
     * Get stroj
     *
     * @return int
     */
    public function getStroj()
    {
        return $this->stroj;
    }

    /**
     * Set casvzniku
     *
     * @param \DateTime $casvzniku
     *
     * @return Porucha
     */
    public function setCasvzniku($casvzniku)
    {
        $this->casvzniku = $casvzniku;

        return $this;
    }

    /**
     * Get casvzniku
     *
     * @return \DateTime
     */
    public function getCasvzniku()
    {
        return $this->casvzniku;
    }

    /**
     * Set oblastpriciny
     *
     * @param string $oblastpriciny
     *
     * @return Porucha
     */
    public function setOblastpriciny($oblastpriciny)
    {
        $this->oblastpriciny = $oblastpriciny;

        return $this;
    }

    /**
     * Get oblastpriciny
     *
     * @return string
     */
    public function getOblastpriciny()
    {
        return $this->oblastpriciny;
    }

    /**
     * Set priorita
     *
     * @param integer $priorita
     *
     * @return Porucha
     */
    public function setPriorita($priorita)
    {
        $this->priorita = $priorita;

        return $this;
    }

    /**
     * Get priorita
     *
     * @return int
     */
    public function getPriorita()
    {
        return $this->priorita;
    }

    /**
     * Set poznamka
     *
     * @param string $poznamka
     *
     * @return Porucha
     */
    public function setPoznamka($poznamka)
    {
        $this->poznamka = $poznamka;

        return $this;
    }

    /**
     * Get poznamka
     *
     * @return string
     */
    public function getPoznamka()
    {
        return $this->poznamka;
    }
}

