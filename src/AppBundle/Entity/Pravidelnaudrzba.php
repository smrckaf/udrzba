<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pravidelnaudrzba
 *
 * @ORM\Table(name="pravidelnaudrzba")
 * @ORM\Entity()
 */
class Pravidelnaudrzba
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Stroj")
     * @ORM\JoinColumn(name="id_stroje")
     */
    private $idStroje;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_udrzbyod", type="datetime")
     */
    private $datumUdrzbyod;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_udrzbydo", type="datetime")
     */
    private $datumUdrzbydo;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pracovnik")
     * @ORM\JoinColumn(name="kdozadal", nullable=true)
     */
    private $kdozadal;



    /**
     * @var string
     *
     * @ORM\Column(name="popis_udrzby", type="string", length=255, nullable=true)
     */
    private $popisUdrzby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="provedeni", type="datetime", nullable=true)
     */
    private $provedeni;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pracovnik")
     * @ORM\JoinColumn(name="provedl", nullable=true)
     */
    private $provedl;

    /**
     * @var string
     *
     * @ORM\Column(name="poznudrzbare", type="string", length=255, nullable=true)
     */
    private $poznUdrzbare;




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
     * Set idStroje
     *
     * @param integer $idStroje
     *
     * @return Pravidelnaudrzba
     */
    public function setIdStroje($idStroje)
    {
        $this->idStroje = $idStroje;

        return $this;
    }

    /**
     * Get idStroje
     *
     * @return int
     */
    public function getIdStroje()
    {
        return $this->idStroje;
    }

    /**
     * Set datumUdrzbyod
     *
     * @param \DateTime $datumUdrzbyod
     *
     * @return Pravidelnaudrzba
     */
    public function setDatumUdrzbyod($datumUdrzbyod)
    {
        $this->datumUdrzbyod = $datumUdrzbyod;

        return $this;
    }

    /**
     * Get datumUdrzbyod
     *
     * @return \DateTime
     */
    public function getDatumUdrzbyod()
    {
        return $this->datumUdrzbyod;
    }


    /**
     * Set datumUdrzbydo
     *
     * @param \DateTime $datumUdrzbydo
     *
     * @return Pravidelnaudrzba
     */
    public function setDatumUdrzbydo($datumUdrzbydo)
    {
        $this->datumUdrzbydo = $datumUdrzbydo;

        return $this;
    }

    /**
     * Get datumUdrzbydo
     *
     * @return \DateTime
     */
    public function getDatumUdrzbydo()
    {
        return $this->datumUdrzbydo;
    }

    /**
     * Set popisUdrzby
     *
     * @param string $popisUdrzby
     *
     * @return Pravidelnaudrzba
     */
    public function setPopisUdrzby($popisUdrzby)
    {
        $this->popisUdrzby = $popisUdrzby;

        return $this;
    }


    /**
     * Get popisUdrzby
     *
     * @return string
     */
    public function getPopisUdrzby()
    {
        return $this->popisUdrzby;
    }

    /**
     * Set poznUdrzbare
     *
     * @param string $poznUdrzbare
     *
     * @return Pravidelnaudrzba
     */
    public function setPoznUdrzbare($poznUdrzbare)
    {
        $this->poznUdrzbare = $poznUdrzbare;

        return $this;
    }

    /**
     * Get poznudrzbare
     *
     * @return string
     */
    public function getPoznUdrzbare()
    {
        return $this->poznUdrzbare;
    }



    /**
     * Set provedeni
     *
     * @param \DateTime $provedeni
     *
     * @return Pravidelnaudrzba
     */
    public function setProvedeni($provedeni)
    {
        $this->provedeni = $provedeni;

        return $this;
    }

    /**
     * Get provedeni
     *
     * @return \DateTime
     */
    public function getProvedeni()
    {
        return $this->provedeni;
    }

    /**
     * Set provedl
     *
     * @param integer $provedl
     *
     * @return Pravidelnaudrzba
     */
    public function setProvedl($provedl)
    {
        $this->provedl = $provedl;

        return $this;
    }

    /**
     * Get provedl
     *
     * @return int
     */
    public function getProvedl()
    {
        return $this->provedl;
    }

    /**
     * Set kdozadal
     *
     * @param integer $kdozadal
     *
     * @return Pravidelnaudrzba
     */
    public function setKdozadal($kdozadal)
    {
        $this->kdozadal = $kdozadal;

        return $this;
    }

    /**
     * Get kdozadal
     *
     * @return int
     */
    public function getKdozadal()
    {
        return $this->kdozadal;
    }


}

