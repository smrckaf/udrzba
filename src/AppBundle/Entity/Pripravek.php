<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 28.01.2019
 * Time: 10:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Pripravek
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
    private $vyrobce;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typ;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oznaceni;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rokVyroby;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $poznamka;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nazev;

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
     * @return mixed
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * @param mixed $typ
     */
    public function setTyp($typ)
    {
        $this->typ = $typ;
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
     * @return int
     */
    public function getRokVyroby()
    {
        return $this->rokVyroby;
    }

    /**
     * @param int $rokVyroby
     */
    public function setRokVyroby($rokVyroby)
    {
        $this->rokVyroby = $rokVyroby;
    }

    /**
     * @return mixed
     */
    public function getPoznamka()
    {
        return $this->poznamka;
    }

    /**
     * @param mixed $poznamka
     */
    public function setPoznamka($poznamka)
    {
        $this->poznamka = $poznamka;
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
}