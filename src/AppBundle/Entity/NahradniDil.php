<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 28.01.2019
 * Time: 10:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class NahradniDil
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $oznaceni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nazev;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vyrobce;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pocetKusu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cenaZaKusBezDPH;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zivotnost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kontrola;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File()
     */
    private $dokument;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dodavatel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kontaktNaDodavatele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Stroj")
     */
    private $stroj;

    /**
     * Pripravek constructor.
     * @param $stroj
     */
    public function __construct(Stroj $stroj)
    {
        $this->stroj = $stroj;
    }

    /**
     * @return mixed
     */
    public function getStroj()
    {
        return $this->stroj;
    }

    /**
     * @param mixed $stroj
     */
    public function setStroj($stroj)
    {
        $this->stroj = $stroj;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOznaceni()
    {
        return $this->oznaceni;
    }

    /**
     * @param mixed $oznaceni
     */
    public function setOznaceni($oznaceni)
    {
        $this->oznaceni = $oznaceni;
    }

    /**
     * @return mixed
     */
    public function getNazev()
    {
        return $this->nazev;
    }

    /**
     * @param mixed $nazev
     */
    public function setNazev($nazev)
    {
        $this->nazev = $nazev;
    }

    /**
     * @return mixed
     */
    public function getVyrobce()
    {
        return $this->vyrobce;
    }

    /**
     * @param mixed $vyrobce
     */
    public function setVyrobce($vyrobce)
    {
        $this->vyrobce = $vyrobce;
    }

    /**
     * @return int
     */
    public function getPocetKusu()
    {
        return $this->pocetKusu;
    }

    /**
     * @param int $pocetKusu
     */
    public function setPocetKusu($pocetKusu)
    {
        $this->pocetKusu = $pocetKusu;
    }

    /**
     * @return mixed
     */
    public function getCenaZaKusBezDPH()
    {
        return $this->cenaZaKusBezDPH;
    }

    /**
     * @param mixed $cenaZaKusBezDPH
     */
    public function setCenaZaKusBezDPH($cenaZaKusBezDPH)
    {
        $this->cenaZaKusBezDPH = $cenaZaKusBezDPH;
    }

    /**
     * @return mixed
     */
    public function getZivotnost()
    {
        return $this->zivotnost;
    }

    /**
     * @param mixed $zivotnost
     */
    public function setZivotnost($zivotnost)
    {
        $this->zivotnost = $zivotnost;
    }

    /**
     * @return mixed
     */
    public function getKontrola()
    {
        return $this->kontrola;
    }

    /**
     * @param mixed $kontrola
     */
    public function setKontrola($kontrola)
    {
        $this->kontrola = $kontrola;
    }

    /**
     * @return mixed
     */
    public function getDokument()
    {
        return $this->dokument;
    }

    /**
     * @param mixed $dokument
     */
    public function setDokument($dokument)
    {
        $this->dokument = $dokument;
    }

    /**
     * @return mixed
     */
    public function getDodavatel()
    {
        return $this->dodavatel;
    }

    /**
     * @param mixed $dodavatel
     */
    public function setDodavatel($dodavatel)
    {
        $this->dodavatel = $dodavatel;
    }

    /**
     * @return mixed
     */
    public function getKontaktNaDodavatele()
    {
        return $this->kontaktNaDodavatele;
    }

    /**
     * @param mixed $kontaktNaDodavatele
     */
    public function setKontaktNaDodavatele($kontaktNaDodavatele)
    {
        $this->kontaktNaDodavatele = $kontaktNaDodavatele;
    }

    /**
     * @return mixed
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param mixed $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }
}