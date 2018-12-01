<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pravidelnaudrzba
 *
 * @ORM\Table(name="pravidelnaudrzba")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PravidelnaudrzbaRepository")
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
     * @ORM\Column(name="id_stroje", type="integer")
     */
    private $idStroje;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_udrzby", type="datetime")
     */
    private $datumUdrzby;

    /**
     * @var string
     *
     * @ORM\Column(name="popis_udrzby", type="string", length=255)
     */
    private $popisUdrzby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="provedeni", type="datetime")
     */
    private $provedeni;

    /**
     * @var int
     *
     * @ORM\Column(name="provedl", type="integer")
     */
    private $provedl;

    /**
     * @var string
     *
     * @ORM\Column(name="poznudrzbare", type="string", length=255)
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
     * Set datumUdrzby
     *
     * @param \DateTime $datumUdrzby
     *
     * @return Pravidelnaudrzba
     */
    public function setDatumUdrzby($datumUdrzby)
    {
        $this->datumUdrzby = $datumUdrzby;

        return $this;
    }

    /**
     * Get datumUdrzby
     *
     * @return \DateTime
     */
    public function getDatumUdrzby()
    {
        return $this->datumUdrzby;
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
}

