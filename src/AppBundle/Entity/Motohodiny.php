<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 10.01.2019
 * Time: 17:35
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Motohodiny
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $hodnota;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $zapsano;

    /**
     * @var Stroj
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Stroj")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stroj;

    /**
     * @var Motohodiny
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Motohodiny")
     * @ORM\JoinColumn(nullable=true)
     */
    private $predchoziMotohodiny;

    /**
     * @var Motohodiny
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Motohodiny")
     * @ORM\JoinColumn(nullable=true)
     */
    private $nasledujiciMotohodiny;

    /**
     * Motohodiny constructor.
     * @param Stroj $stroj
     */
    public function __construct(Stroj $stroj)
    {
        $this->stroj = $stroj;
        $this->zapsano = new \DateTime;
    }

    /**
     * @return integer
     */
    public function getRozdilOdPredchoziho()
    {
        return ($this->getPredchoziMotohodiny() !== null ? $this->hodnota - $this->getPredchoziMotohodiny()->getHodnota() : 0);
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
     * @return int
     */
    public function getHodnota()
    {
        return $this->hodnota;
    }

    /**
     * @param int $hodnota
     */
    public function setHodnota($hodnota)
    {
        $this->hodnota = $hodnota;
    }

    /**
     * @return \DateTime
     */
    public function getZapsano()
    {
        return $this->zapsano;
    }

    /**
     * @param \DateTime $zapsano
     */
    public function setZapsano($zapsano)
    {
        $this->zapsano = $zapsano;
    }

    /**
     * @return Stroj
     */
    public function getStroj()
    {
        return $this->stroj;
    }

    /**
     * @param Stroj $stroj
     */
    public function setStroj($stroj)
    {
        $this->stroj = $stroj;
    }

    /**
     * @return Motohodiny
     */
    public function getPredchoziMotohodiny()
    {
        return $this->predchoziMotohodiny;
    }

    /**
     * @param Motohodiny $predchoziMotohodiny
     */
    public function setPredchoziMotohodiny($predchoziMotohodiny)
    {
        $this->predchoziMotohodiny = $predchoziMotohodiny;
    }

    /**
     * @return Motohodiny
     */
    public function getNasledujiciMotohodiny()
    {
        return $this->nasledujiciMotohodiny;
    }

    /**
     * @param Motohodiny $nasledujiciMotohodiny
     */
    public function setNasledujiciMotohodiny($nasledujiciMotohodiny)
    {
        $this->nasledujiciMotohodiny = $nasledujiciMotohodiny;
    }
}