<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:38
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Kompetence;
use AppBundle\Entity\Pracovnik;
use AppBundle\Entity\Stroj;
use Doctrine\ORM\EntityManagerInterface;

class UdrzbaManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UdrzbaManager constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Pracovnik[]|array
     */
    public function getAllPracovnici()
    {
        return $this->em->getRepository(Pracovnik::class)->findAll();
    }

    public function getAllStroje()
    {
        return $this->em->getRepository(Stroj::class)->findAll();
    }

    public function ulozitStroj(Stroj $stroj)
    {
        if ($stroj->getId() === null)
            $this->em->persist($stroj);
        $this->em->flush();
    }

    public function smazatStroj(Stroj $stroj)
    {
        $this->em->remove($stroj);
        $this->em->flush();
    }

    public function ulozitPracovnika(Pracovnik $pracovnik)
    {
        if ($pracovnik->getId() === null)
            $this->em->persist($pracovnik);

        $this->em->flush();
    }

    public function smazatPracovnika(Pracovnik $pracovnik)
    {
        $this->em->remove($pracovnik);
        $this->em->flush();
    }

    public function getKompetencePracovnika(Pracovnik $pracovnik)
    {
        return $this->em->getRepository(Kompetence::class)->findBy(['pracovnik' => $pracovnik]);
    }

    public function getExampleDashboardPoruchy()
    {
        $result = [];

        $result[0]["poradi"] = "1";
        $result[0]["idUkolu"] = "ID1";
        $result[0]["priorita"] = "A";
        $result[0]["pracoviste"] = "Stroj1";
        $result[0]["datumVzniku"] = "18.10.18";
        $result[0]["casVzniku"] = "12:05";
        $result[0]["casPotvrzeni"] = "12:08";
        $result[0]["status"] = "hotovo";
        $result[0]["resitel"] = "Pepa Novotný";

        $result[1]["poradi"] = "3";
        $result[1]["idUkolu"] = "ID3";
        $result[1]["priorita"] = "B";
        $result[1]["pracoviste"] = "Stroj10";
        $result[1]["datumVzniku"] = "18.10.18";
        $result[1]["casVzniku"] = "14:13";
        $result[1]["casPotvrzeni"] = "14:25";
        $result[1]["status"] = "probíhá oprava";
        $result[1]["resitel"] = "Pepa Novotný";

        $result[2]["poradi"] = "5";
        $result[2]["idUkolu"] = "ID5";
        $result[2]["priorita"] = "C";
        $result[2]["pracoviste"] = "Stroj7";
        $result[2]["datumVzniku"] = "18.10.18";
        $result[2]["casVzniku"] = "16:31";
        $result[2]["casPotvrzeni"] = "NA";
        $result[2]["status"] = "nepřiřazeno";
        $result[2]["resitel"] = "";

        $result[3]["poradi"] = "6";
        $result[3]["idUkolu"] = "ID6";
        $result[3]["priorita"] = "C";
        $result[3]["pracoviste"] = "Stroj20";
        $result[3]["datumVzniku"] = "18.10.18";
        $result[3]["casVzniku"] = "17:00";
        $result[3]["casPotvrzeni"] = "NA";
        $result[3]["status"] = "nepřiřazeno";
        $result[3]["resitel"] = "";

        return $result;
    }

    public function getExampleDashboardNeprirazene()
    {
        $result = [];

        $result[0]["poradi"] = "6";
        $result[0]["idUkolu"] = "ID6";
        $result[0]["priorita"] = "C";
        $result[0]["pracoviste"] = "Stroj20";
        $result[0]["datumVzniku"] = "18.10.18";
        $result[0]["casVzniku"] = "17:00";
        $result[0]["casPotvrzeni"] = "NA";
        $result[0]["status"] = "nepřiřazeno";
        $result[0]["resitel"] = "";

        $result[1]["poradi"] = "5";
        $result[1]["idUkolu"] = "ID5";
        $result[1]["priorita"] = "C";
        $result[1]["pracoviste"] = "Stroj7";
        $result[1]["datumVzniku"] = "18.10.18";
        $result[1]["casVzniku"] = "16:31";
        $result[1]["casPotvrzeni"] = "NA";
        $result[1]["status"] = "nepřiřazeno";
        $result[1]["resitel"] = "";

        return $result;
    }

    public function getExampleDashboardPracovnici()
    {
        $result = [];

        $result[0]["jmeno"] = "Pepa Novotný";
        $result[0]["status"] = "probíhá oprava";
        $result[0]["pracoviste"] = "Stroj10";
        $result[0]["od"] = "14:35";
        $result[0]["do"] = "";

        $result[1]["jmeno"] = "Honza Ondráček";
        $result[1]["status"] = "probíhá oprava";
        $result[1]["pracoviste"] = "Stroj3";
        $result[1]["od"] = "13:30";
        $result[1]["do"] = "";

        $result[2]["jmeno"] = "Pavel Kos";
        $result[2]["status"] = "není určeno";
        $result[2]["pracoviste"] = "-";
        $result[2]["od"] = "-";
        $result[2]["do"] = "";

        return $result;
    }

    public function getExampleDashboardPrehled()
    {
        $result = [];

        $result["pocetOtevrenych"] = "2";
        $result["pocetBezNotifikace"] = "1";
        $result["pocetUzavrenych"] = "2";
        $result["pocetMinut"] = "127";

        return $result;
    }
}