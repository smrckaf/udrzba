<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KontaktniOsoba
 *
 * @ORM\Table(name="kontaktni_osoba")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\KontaktniOsobaRepository")
 */
class KontaktniOsoba
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
     * @ORM\Column(name="jmeno", type="string", length=255)
     */
    private $jmeno;

    /**
     * @var string
     *
     * @ORM\Column(name="prijmeni", type="string", length=255)
     */
    private $prijmeni;

    /**
     * @var string
     *
     * @ORM\Column(name="pozice", type="string", length=255, nullable=true)
     */
    private $pozice;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefon", type="string", length=255, nullable=true)
     */
    private $telefon;

    /**
     * @var string
     *
     * @ORM\Column(name="poznamka", type="string", length=255, nullable=true)
     */
    private $poznamka;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Firma")
     */
    private $firma;


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
     * Set jmeno
     *
     * @param string $jmeno
     *
     * @return KontaktniOsoba
     */
    public function setJmeno($jmeno)
    {
        $this->jmeno = $jmeno;

        return $this;
    }

    /**
     * Get jmeno
     *
     * @return string
     */
    public function getJmeno()
    {
        return $this->jmeno;
    }

    /**
     * Set prijmeni
     *
     * @param string $prijmeni
     *
     * @return KontaktniOsoba
     */
    public function setPrijmeni($prijmeni)
    {
        $this->prijmeni = $prijmeni;

        return $this;
    }

    /**
     * Get prijmeni
     *
     * @return string
     */
    public function getPrijmeni()
    {
        return $this->prijmeni;
    }

    /**
     * Set pozice
     *
     * @param string $pozice
     *
     * @return KontaktniOsoba
     */
    public function setPozice($pozice)
    {
        $this->pozice = $pozice;

        return $this;
    }

    /**
     * Get pozice
     *
     * @return string
     */
    public function getPozice()
    {
        return $this->pozice;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return KontaktniOsoba
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
     * Set telefon
     *
     * @param string $telefon
     *
     * @return KontaktniOsoba
     */
    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;

        return $this;
    }

    /**
     * Get telefon
     *
     * @return string
     */
    public function getTelefon()
    {
        return $this->telefon;
    }

    /**
     * Set poznamka
     *
     * @param string $poznamka
     *
     * @return KontaktniOsoba
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
     * Set firma
      * @param mixed $firma
      */
    public function setFirma($firma)
    {
        $this->firma = $firma;

            }

    /**
     * Get firma
     *
     * @return mixed
     */
    public function getFirma()
    {
        return $this->firma;
    }

}

