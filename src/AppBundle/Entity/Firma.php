<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Firma
 *
 * @ORM\Table(name="firma")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FirmaRepository")
 */
class Firma
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
     * @var string
     *
     * @ORM\Column(name="nazev", type="string", length=255)
     */
    private $nazev;

    /**
     * @var string
     *
     * @ORM\Column(name="adresa", type="string", length=255)
     */
    private $adresa;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @var string
     *
     * @ORM\Column(name="kontakt", type="string", length=255, nullable=true)
     */
    private $kontakt;

    /**
     * @var string
     *
     * @ORM\Column(name="ico", type="string", length=255, nullable=true)
     */
    private $ico;

    /**
     * @var string
     *
     * @ORM\Column(name="dic", type="string", length=255, nullable=true)
     */
    private $dic;

    /**
     * @var int
     *
     * @ORM\Column(name="dodacilhuta", type="integer", nullable=true)
     */
    private $dodacilhuta;


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
     * Set nazev
     *
     * @param string $nazev
     *
     * @return Firma
     */
    public function setNazev($nazev)
    {
        $this->nazev = $nazev;

        return $this;
    }

    /**
     * Get nazev
     *
     * @return string
     */
    public function getNazev()
    {
        return $this->nazev;
    }

    /**
     * Set adresa
     *
     * @param string $adresa
     *
     * @return Firma
     */
    public function setAdresa($adresa)
    {
        $this->adresa = $adresa;

        return $this;
    }

    /**
     * Get adresa
     *
     * @return string
     */
    public function getAdresa()
    {
        return $this->adresa;
    }

    /**
     * Set web
     *
     * @param string $web
     *
     * @return Firma
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set kontakt
     *
     * @param string $kontakt
     *
     * @return Firma
     */
    public function setKontakt($kontakt)
    {
        $this->kontakt = $kontakt;

        return $this;
    }

    /**
     * Get kontakt
     *
     * @return string
     */
    public function getKontakt()
    {
        return $this->kontakt;
    }

    /**
     * Set ico
     *
     * @param string $ico
     *
     * @return Firma
     */
    public function setIco($ico)
    {
        $this->ico = $ico;

        return $this;
    }

    /**
     * Get ico
     *
     * @return string
     */
    public function getIco()
    {
        return $this->ico;
    }

    /**
     * Set dic
     *
     * @param string $dic
     *
     * @return Firma
     */
    public function setDic($dic)
    {
        $this->dic = $dic;

        return $this;
    }

    /**
     * Get dic
     *
     * @return string
     */
    public function getDic()
    {
        return $this->dic;
    }

    /**
     * Set dodacilhuta
     *
     * @param integer $dodacilhuta
     *
     * @return Firma
     */
    public function setDodacilhuta($dodacilhuta)
    {
        $this->dodacilhuta = $dodacilhuta;

        return $this;
    }

    /**
     * Get dodacilhuta
     *
     * @return int
     */
    public function getDodacilhuta()
    {
        return $this->dodacilhuta;
    }
}

