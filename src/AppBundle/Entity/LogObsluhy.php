<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogObsluhy
 *
 * @ORM\Table(name="log_obsluhy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogObsluhyRepository")
 */
class LogObsluhy
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
     * @ORM\Column(name="idprevzal", type="integer")
     */
    private $idprevzal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="konec", type="datetime", nullable=true)
     */
    private $konec;


    /**
     * @var int
     *
     * @ORM\Column(name="typ", type="integer", nullable=true)
     */
    private $typ;

    /**
     * @var string
     *
     * @ORM\Column(name="poznamka", type="string", length=255, nullable=true)
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
     * Set idprevzal
     *
     * @param integer $idprevzal
     *
     * @return LogObsluhy
     */
    public function setIdprevzal($idprevzal)
    {
        $this->idprevzal = $idprevzal;

        return $this;
    }

    /**
     * Get idprevzal
     *
     * @return int
     */
    public function getIdprevzal()
    {
        return $this->idprevzal;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return LogObsluhy
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set konec
     *
     * @param \DateTime $konec
     *
     * @return LogObsluhy
     */
    public function setKonec($konec)
    {
        $this->konec = $konec;

        return $this;
    }

    /**
     * Get konec
     *
     * @return \DateTime
     */
    public function getKonec()
    {
        return $this->konec;
    }

    /**
     * Set typ
     *
     * @param integer $typ
     *
     * @return LogObsluhy
     */
    public function setTyp($typ)
    {
        $this->typ = $typ;

        return $this;
    }

    /**
     * Get typ
     *
     * @return int
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * Set poznamka
     *
     * @param string $poznamka
     *
     * @return LogObsluhy
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
