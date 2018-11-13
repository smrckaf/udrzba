<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pracovnik
 *
 * @ORM\Table(name="pracovnik")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PracovnikRepository")
 */
class Pracovnik
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
     * @var bool
     *
     * @ORM\Column(name="smennost", type="boolean")
     */
    private $smennost;

    /**
     * @var string
     *
     * @ORM\Column(name="kvalifikace", type="string", length=255)
     */
    private $kvalifikace;


    /**
     * @var float
     *
     * @ORM\Column(name="hodsazba", type="float")
     */
    private $hodsazba;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="poznamka", type="string", length=255)
     */
    private $poznamka;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;


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
     * Set smennost
     *
     * @param boolean $smennost
     *
     * @return Pracovnik
     */
    public function setSmennost($smennost)
    {
        $this->smennost = $smennost;

        return $this;
    }

    /**
     * Get smennost
     *
     * @return bool
     */
    public function getSmennost()
    {
        return $this->smennost;
    }

    /**
     * Set kvalifikace
     *
     * @param string $kvalifikace
     *
     * @return Pracovnik
     */
    public function setKvalifikace($kvalifikace)
    {
        $this->kvalifikace = $kvalifikace;

        return $this;
    }

    /**
     * Get kvalifikace
     *
     * @return string
     */
    public function getKvalifikace()
    {
        return $this->kvalifikace;
    }



    /**
     * Set hodsazba
     *
     * @param float $hodsazba
     *
     * @return Pracovnik
     */
    public function setHodsazba($hodsazba)
    {
        $this->hodsazba = $hodsazba;

        return $this;
    }

    /**
     * Get hodsazba
     *
     * @return float
     */
    public function getHodsazba()
    {
        return $this->hodsazba;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Pracovnik
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set poznamka
     *
     * @param string $poznamka
     *
     * @return Pracovnik
     */
    public function setPoznamka($poznamka)
    {
        $this->poznamka = $poznamka;

        return $this;
    }

    /**
     * Get poznamka
     *
     * @return string
     */
    public function getPoznamka()
    {
        return $this->poznamka;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Pracovnik
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Pracovnik
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}

