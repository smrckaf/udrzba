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
    private $idOld;

    /**
     * @var string
     *
     * @ORM\Column(name="nazev", type="string", length=255)
     */
    private $nazev;


    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;



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
     * Set idOld
     *
     * @param integer $idOld
     *
     * @return Stroj
     */
    public function setIdOld($idOld)
    {
        $this->idOld = $idOld;

        return $this;
    }

    /**
     * Get idOld
     *
     * @return int
     */
    public function getIdOld()
    {
        return $this->idOld;
    }






    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Stroj
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }








    /**
     * Set nazev
     *
     * @param string $nazev
     *
     * @return Stroj
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
}

