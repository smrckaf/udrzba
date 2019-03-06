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
     * @ORM\ManyToOne(targetEntity="Stroj")
     * @ORM\JoinColumn(name="stroj")
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
     * @var \DateTime
     *
     * @ORM\Column(name="vyreseno", type="datetime" ,nullable=true )
     */
    private $vyreseno;





    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Prevzal", mappedBy="idPoruchy")
     */
    private $prevzate;


    public function getStrojId(){
        return $this->stroj->getId();
    }
    public function getStrojNazev(){
        return $this->stroj->getNazev();
    }


    /**
     * Get prevzate
     *
     * @return int
     */
    public function getPrevzate()
    {
        return $this->prevzate;
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
    /**
     * Set vyreseno
     *
     * @param \DateTime $vyreseno
     *
     * @return Porucha
     */
    public function setVyreseno($vyreseno)
    {
        $this->vyreseno = $vyreseno;

        return $this;
    }

    /**
     * Get vyreseno
     *
     * @return \DateTime
     */
    public function getVyreseno()
    {
        return $this->vyreseno;
    }

    /**
     * Get rozdilcasu
     *
     * @return \DateTime
     */

}





