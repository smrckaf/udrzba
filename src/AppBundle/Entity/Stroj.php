<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stroj
 *
 * @ORM\Table(name="stroj")
 * @ORM\Entity()
 */
class Stroj
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_old", type="integer")
     */
    private $id1pcontrol;

    /**
     * @var string
     *
     * @ORM\Column(name="nazev", type="string", length=255)
     */
    private $nazev;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Skupina")
     */
    private $skupina;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lokace")
     */
    private $lokace;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\KmenovaData")
     */
    private $kmenovaData;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $jeAktivni = true;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId1pcontrol()
    {
        return $this->id1pcontrol;
    }

    /**
     * @param int $id1pcontrol
     */
    public function setId1pcontrol($id1pcontrol)
    {
        $this->id1pcontrol = $id1pcontrol;
    }

    /**
     * @return string
     */
    public function getNazev()
    {
        return $this->nazev;
    }

    /**
     * @param string $nazev
     */
    public function setNazev($nazev)
    {
        $this->nazev = $nazev;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSkupina()
    {
        return $this->skupina;
    }

    /**
     * @param mixed $skupina
     */
    public function setSkupina($skupina)
    {
        $this->skupina = $skupina;
    }

    /**
     * @return mixed
     */
    public function getLokace()
    {
        return $this->lokace;
    }

    /**
     * @param mixed $lokace
     */
    public function setLokace($lokace)
    {
        $this->lokace = $lokace;
    }

    /**
     * @return mixed
     */
    public function getKmenovaData()
    {
        return $this->kmenovaData;
    }

    /**
     * @param mixed $kmenovaData
     */
    public function setKmenovaData($kmenovaData)
    {
        $this->kmenovaData = $kmenovaData;
    }

    /**
     * @return mixed
     */
    public function getJeAktivni()
    {
        return $this->jeAktivni;
    }

    /**
     * @param mixed $jeAktivni
     */
    public function setJeAktivni($jeAktivni)
    {
        $this->jeAktivni = $jeAktivni;
    }


}

