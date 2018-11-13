<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logporuch
 *
 * @ORM\Table(name="logporuch")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogporuchRepository")
 */
class Logporuch
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
     * @ORM\Column(name="idprevzal", type="integer")
     */
    private $idprevzal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pretusenistrojbezi", type="datetime")
     */
    private $pretusenistrojbezi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prerusenistrojestoji", type="datetime")
     */
    private $prerusenistrojestoji;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pokracovani", type="datetime")
     */
    private $pokracovani;


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
     * Set idprevzal
     *
     * @param integer $idprevzal
     *
     * @return Logporuch
     */
    public function setIdprevzal($idprevzal)
    {
        $this->idprevzal = $idprevzal;

        return $this;
    }

    /**
     * Get idprevzal
     *
     * @return int
     */
    public function getIdprevzal()
    {
        return $this->idprevzal;
    }

    /**
     * Set pretusenistrojbezi
     *
     * @param \DateTime $pretusenistrojbezi
     *
     * @return Logporuch
     */
    public function setPretusenistrojbezi($pretusenistrojbezi)
    {
        $this->pretusenistrojbezi = $pretusenistrojbezi;

        return $this;
    }

    /**
     * Get pretusenistrojbezi
     *
     * @return \DateTime
     */
    public function getPretusenistrojbezi()
    {
        return $this->pretusenistrojbezi;
    }

    /**
     * Set prerusenistrojestoji
     *
     * @param \DateTime $prerusenistrojestoji
     *
     * @return Logporuch
     */
    public function setPrerusenistrojestoji($prerusenistrojestoji)
    {
        $this->prerusenistrojestoji = $prerusenistrojestoji;

        return $this;
    }

    /**
     * Get prerusenistrojestoji
     *
     * @return \DateTime
     */
    public function getPrerusenistrojestoji()
    {
        return $this->prerusenistrojestoji;
    }

    /**
     * Set pokracovani
     *
     * @param \DateTime $pokracovani
     *
     * @return Logporuch
     */
    public function setPokracovani($pokracovani)
    {
        $this->pokracovani = $pokracovani;

        return $this;
    }

    /**
     * Get pokracovani
     *
     * @return \DateTime
     */
    public function getPokracovani()
    {
        return $this->pokracovani;
    }
}

