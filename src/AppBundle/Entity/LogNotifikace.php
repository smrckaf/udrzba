<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogNotifikace
 *
 * @ORM\Table(name="log_notifikace")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogNotifikaceRepository")
 */
class LogNotifikace
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
     * @ORM\Column(name="idzarizeni", type="string", length=255)
     */
    private $idzarizeni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datumcas", type="datetime")
     */
    private $datumcas;

    /**
     * @var int
     *
     * @ORM\Column(name="porucha", type="integer")
     */
    private $porucha;

    /**
     * @var int
     *
     * @ORM\Column(name="stroj", type="integer")
     */
    private $stroj;


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
     * Set idzarizeni
     *
     * @param string $idzarizeni
     *
     * @return LogNotifikace
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
     * Set datumcas
     *
     * @param \DateTime $datumcas
     *
     * @return LogNotifikace
     */
    public function setDatumcas($datumcas)
    {
        $this->datumcas = $datumcas;

        return $this;
    }

    /**
     * Get datumcas
     *
     * @return \DateTime
     */
    public function getDatumcas()
    {
        return $this->datumcas;
    }

    /**
     * Set porucha
     *
     * @param int $porucha
     *
     * @return LogNotifikace
     */
    public function setPorucha($porucha)
    {
        $this->porucha = $porucha;

        return $this;
    }

    /**
     * Get porucha
     *
     * @return int
     */
    public function getPorucha()
    {
        return $this->porucha;
    }

    /**
     * Set stroj
     *
     * @param integer $stroj
     *
     * @return LogNotifikace
     */
    public function setStroj($stroj)
    {
        $this->stroj = $stroj;

        return $this;
    }

    /**
     * Get stroj
     *
     * @return int
     */
    public function getStroj()
    {
        return $this->stroj;
    }
}

