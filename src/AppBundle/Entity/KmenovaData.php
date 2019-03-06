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
class KmenovaData
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
    private $vyrobniCislo;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Image()
     */
    private $obrazek1;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Image()
     */
    private $obrazek2;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Image()
     */
    private $obrazek3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stredisko;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $uvedenoDoProvozu;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $zarukaDo;

    /**
     * KmenovaData constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getStredisko()
    {
        return $this->stredisko;
    }

    /**
     * @param mixed $stredisko
     */
    public function setStredisko($stredisko)
    {
        $this->stredisko = $stredisko;
    }

    /**
     * @return mixed
     */
    public function getUvedenoDoProvozu()
    {
        return $this->uvedenoDoProvozu;
    }

    /**
     * @param mixed $uvedenoDoProvozu
     */
    public function setUvedenoDoProvozu($uvedenoDoProvozu)
    {
        $this->uvedenoDoProvozu = $uvedenoDoProvozu;
    }

    /**
     * @return mixed
     */
    public function getZarukaDo()
    {
        return $this->zarukaDo;
    }

    /**
     * @param mixed $zarukaDo
     */
    public function setZarukaDo($zarukaDo)
    {
        $this->zarukaDo = $zarukaDo;
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
    public function getVyrobniCislo()
    {
        return $this->vyrobniCislo;
    }

    /**
     * @param mixed $vyrobniCislo
     */
    public function setVyrobniCislo($vyrobniCislo)
    {
        $this->vyrobniCislo = $vyrobniCislo;
    }

    /**
     * @return mixed
     */
    public function getObrazek1()
    {
        return $this->obrazek1;
    }

    /**
     * @param mixed $obrazek1
     */
    public function setObrazek1($obrazek1)
    {
        $this->obrazek1 = $obrazek1;
    }

    /**
     * @return mixed
     */
    public function getObrazek2()
    {
        return $this->obrazek2;
    }

    /**
     * @param mixed $obrazek2
     */
    public function setObrazek2($obrazek2)
    {
        $this->obrazek2 = $obrazek2;
    }

    /**
     * @return mixed
     */
    public function getObrazek3()
    {
        return $this->obrazek3;
    }

    /**
     * @param mixed $obrazek3
     */
    public function setObrazek3($obrazek3)
    {
        $this->obrazek3 = $obrazek3;
    }
}