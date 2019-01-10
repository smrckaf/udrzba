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
class Skupina
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="nazev", type="string", length=255)
     */
    private $nazev;

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
    public function getNazev()
    {
        return $this->nazev;
    }

    /**
     * @param mixed $nazev
     */
    public function setNazev($nazev)
    {
        $this->nazev = $nazev;
    }
}