<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prevzal
 *
 * @ORM\Table(name="prevzal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrevzalRepository")
 */
class Prevzal
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
     * @ORM\Column(name="id_poruchy", type="integer")
     */
    private $idPoruchy;

    /**
     * @var int
     *
     * @ORM\Column(name="id_pracovnika", type="integer")
     */
    private $idPracovnika;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prevzetidatcas", type="datetime")
     */
    private $prevzetidatcas;

    /**
     * @var int
     *
     * @ORM\Column(name="role_obsluhy", type="integer")
     */
    private $role_obsluhy;








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
     * Set idPoruchy
     *
     * @param integer $idPoruchy
     *
     * @return Prevzal
     */
    public function setIdPoruchy($idPoruchy)
    {
        $this->idPoruchy = $idPoruchy;

        return $this;
    }

    /**
     * Get idPoruchy
     *
     * @return int
     */
    public function getIdPoruchy()
    {
        return $this->idPoruchy;
    }

    /**
     * Set idPracovnika
     *
     * @param integer $idPracovnika
     *
     * @return Prevzal
     */
    public function setIdPracovnika($idPracovnika)
    {
        $this->idPracovnika = $idPracovnika;

        return $this;
    }

    /**
     * Get idPracovnika
     *
     * @return int
     */
    public function getIdPracovnika()
    {
        return $this->idPracovnika;
    }

    /**
     * Set prevzetidatcas
     *
     * @param \DateTime $prevzetidatcas
     *
     * @return Prevzal
     */
    public function setPrevzetidatcas($prevzetidatcas)
    {
        $this->prevzetidatcas = $prevzetidatcas;

        return $this;
    }

    /**
     * Get prevzetidatcas
     *
     * @return \DateTime
     */
    public function getPrevzetidatcas()
    {
        return $this->prevzetidatcas;
    }




    /**
     * Set role_obsluhy
     *
     * @param integer $role_obsluhy
     *
     * @return Prevzal
     */
    public function setRole_obsluhy($role_obsluhy)
    {
        $this->role_obsluhy = $role_obsluhy;

        return $this;
    }

    /**
     * Get role_obsluhy
     *
     * @return int
     */
    public function getRole_obsluhy()
    {
        return $this->role_obsluhy;
    }

}

