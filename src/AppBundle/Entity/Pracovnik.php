<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pracovnik
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Pracovnik
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
     * @ORM\Column(name="jmeno", type="string", length=255)
     */
    private $jmeno;

    /**
     * @ORM\Column(name="prijmeni", type="string", length=255)
     */
    private $prijmeni;

    /**
     * @ORM\Column(name="heslo", type="string", length=255)
     */
    private $heslo;

    /**
     * @var bool
     *
     * @ORM\Column(name="smennost", type="boolean")
     */
    private $smennost;

    /**
     * @var string
     *
     * @ORM\Column(name="kvalifikace", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="idzarizeni", type="string", length=255, nullable=true)
     */
    private $idzarizeni;

    /**
     * @var string
     *
     * @ORM\Column(name="poznamka", type="string", length=255, nullable=true)
     */
    private $poznamka;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Stroj")
     * @ORM\JoinColumn(name="stroj_id", referencedColumnName="id")
     */
    private $stroje;

    /**
     * Pracovnik constructor.
     */
    public function __construct()
    {
        $this->stroje = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStroje()
    {
        return $this->stroje;
    }

    /**
     * @param mixed $stroje
     */
    public function setStroje($stroje)
    {
        $this->stroje = $stroje;
    }

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
     * @return mixed
     */
    public function getJmeno()
    {
        return $this->jmeno;
    }

    /**
     * @param mixed $jmeno
     */
    public function setJmeno($jmeno)
    {
        $this->jmeno = $jmeno;
    }

    /**
     * @return mixed
     */
    public function getPrijmeni()
    {
        return $this->prijmeni;
    }

    /**
     * @param mixed $prijmeni
     */
    public function setPrijmeni($prijmeni)
    {
        $this->prijmeni = $prijmeni;
    }

    /**
     * @return mixed
     */
    public function getHeslo()
    {
        return $this->heslo;
    }

    /**
     * @param mixed $heslo
     */
    public function setHeslo($heslo)
    {
        $this->heslo = hash('sha256', $heslo);
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
     * Set idzarizeni
     *
     * @param string $idzarizeni
     *
     * @return Pracovnik
     */
    public function setIdzarizeni($idzarizeni)
    {
        $this->idzarizeni = $idzarizeni;

        return $this;
    }

    /**
     * Get idzarizeni
     *
     * @return string
     */
    public function getIdzarizeni()
    {
        return $this->idzarizeni;
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

