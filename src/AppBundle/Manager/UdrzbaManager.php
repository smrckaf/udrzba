<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:38
 */

namespace AppBundle\Manager;


use AppBundle\Entity\KmenovaData;
use AppBundle\Entity\Kompetence;
use AppBundle\Entity\NahradniDil;
use AppBundle\Entity\Nastroj;
use AppBundle\Entity\Porucha;
use AppBundle\Entity\Pracovnik;
use AppBundle\Entity\Pravidelnaudrzba;
use AppBundle\Entity\Prevzal;
use AppBundle\Entity\Pripravek;
use AppBundle\Entity\Skupina;
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

    public function ulozitPravidelnouUdrzbu(Pravidelnaudrzba $pravidelnaudrzba)
    {
        if ($pravidelnaudrzba->getId() === null)
            $this->em->persist($pravidelnaudrzba);
        $this->em->flush();
    }

    public function smazatPravidelnouUdrzbu(Pravidelnaudrzba $pravidelnaudrzba)
    {
        $this->em->remove($pravidelnaudrzba);
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

    public function getNazvySkupin()
    {
        $skupiny = $this->em->createQuery("SELECT s.nazev FROM " . Skupina::class . " s ORDER BY s.id ASC")
            ->getResult();

        $ret = array_merge([0 => ["nazev" => "Mimo skupinu"]], $skupiny);

        return $ret;
    }

    /**
     * @param $obdobi
     * @return Pravidelnaudrzba[]
     */
    public function getPravidelneUdrzby($obdobi)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p.id, p.provedeni, p.poznUdrzbare, p.popisUdrzby, p.datumUdrzbyod, p.datumUdrzbydo, s.nazev, u.jmeno, u.prijmeni,k.jmeno as kjmeno, k.prijmeni as kprijmeni')
            ->from(Pravidelnaudrzba::class, 'p')
            ->join('p.idStroje', 's')
            ->leftJoin('p.provedl', 'u')
            ->leftJoin('p.kdozadal', 'k')
            ->where('p.datumUdrzbyod > :datumOd')
            ->setParameter('datumOd', new \DateTime);

        switch ($obdobi) {
            case 1:
                $qb
                    ->andWhere('p.datumUdrzbyod <= :datumDo')
                    ->setParameter('datumDo', (new \DateTime)->modify('+7 days'));
                break;
            case 2:
                $qb
                    ->andWhere('p.datumUdrzbyod <= :datumDo')
                    ->setParameter('datumDo', (new \DateTime)->modify('+1 month'));
                break;
            default:
                break;
        }
        return $qb
            ->orderBy('p.datumUdrzbyod', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getKmenovaDataByStroj(Stroj $stroj)
    {
        return $this->em->getRepository(KmenovaData::class)->findOneBy(['stroj' => $stroj]);
    }

    public function ulozitKmenovaData(KmenovaData $kmenovaData)
    {
        if ($kmenovaData->getId() === null)
            $this->em->persist($kmenovaData);
        $this->em->flush();
    }

    public function ulozitPripravek(Pripravek $pripravek)
    {
        if ($pripravek->getId() === null)
            $this->em->persist($pripravek);
        $this->em->flush();
    }

    public function ulozitNastroj(Nastroj $nastroj)
    {
        if ($nastroj->getId() === null)
            $this->em->persist($nastroj);
        $this->em->flush();
    }

    public function smazatPripravek(Pripravek $pripravek)
    {
        $this->em->remove($pripravek);
        $this->em->flush();
    }

    public function smazatNastroj(Nastroj $nastroj)
    {
        $this->em->remove($nastroj);
        $this->em->flush();
    }

    public function getPripravkyByStroj(Stroj $stroj)
    {
        return $this->em->getRepository(Pripravek::class)->findBy(['stroj' => $stroj]);
    }

    public function getNastrojeByStroj(Stroj $stroj)
    {
        return $this->em->getRepository(Nastroj::class)->findBy(['stroj' => $stroj]);
    }

    public function getPorucha()
    {
        //$porucha = $this->em->getRepository(Porucha::class)->findAll();
        //if (!$porucha) {
        //   throw new ResourceNotFoundException('Porucha not found');
        // }
        $porucha = $this->em->createQueryBuilder()
            ->select('p, r ')
            ->from(Porucha::class, 'p')
            ->join('p.prevzate', 'r');
        //sem nezapomenout pridat podminku na aktualni cas

        return $porucha->getQuery()->getResult();
    }

    public function getNaprirazeneukoly()
    {
        $neprirazene = $this->em->createQueryBuilder()
            ->select('p, r ')
            ->from(Porucha::class, 'p')
            ->leftJoin('p.prevzate', 'r')
            ->where('r.prevzetidatcas is Null');
        return $neprirazene->getQuery()->getResult();
    }

    public function getPracovniciudrzby()
    {
        $neprirazene = $this->em->createQueryBuilder()
            ->select('prac.jmeno, prac.prijmeni, por.stroj, por.vyreseno, prevz.prevzetidatcas')
            ->from(Pracovnik::class, 'prac')
            ->leftJoin(Prevzal::class, 'prevz', 'WITH', 'prevz.idPracovnika = prac.id')
            ->leftJoin('prevz.idPoruchy', 'por')
            ->where('prac.idzarizeni IS NOT NULL')
            ->orderBy('prac.id', 'ASC');
        return $neprirazene->getQuery()->getResult();
    }

    public function getPocetotevrenychukolu()
    {
        $qb = $this->em->createQueryBuilder()
            ->select('count(por.id)')
            ->from(Porucha::class, 'por')
            ->where('por.vyreseno is NULL');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPocetuzavrenychpripadu()
    {
        $qb = $this->em->createQueryBuilder()
            ->select('count(por.id)')
            ->from(Porucha::class, 'por')
            ->where('por.vyreseno is not NULL');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPocetpripadubeznotifikace()
    {
        $pocetprijatychporuch = $this->em->createQueryBuilder()
            ->select('count(pre.idPoruchy)')
            ->from(Prevzal::class, 'pre')
            ->groupBy('pre.idPoruchy');

        $pocetporuch = $this->em->createQueryBuilder()
            ->select('count(por)')
            ->from(Porucha::class, 'por');

    //sem nezapomenout pridat podminku na aktualni cas
   // return $qb->getQuery()->getSingleScalarResult();
        return $pocetporuch->getQuery()->getSingleScalarResult() -count($pocetprijatychporuch->getQuery()->getResult());


    }
    public function getSoucetcasuold()
    {
        return $this->em->getConnection()->fetchColumn("SELECT sum(TIMESTAMPDIFF(SECOND, casvzniku, vyreseno)) AS sclr_0 FROM porucha where vyreseno is NOT NULL");

    }
    public function getSoucetcasu()
    {
        return $this->em->getConnection()->fetchColumn("SELECT sum(TIMESTAMPDIFF(SECOND, CASE WHEN start < date(now()) THEN date(now()) ELSE start END, CASE WHEN konec IS NULL THEN now() ELSE konec END))/3600 AS sclr_0 FROM log_obsluhy JOIN prevzal ON log_obsluhy.idprevzal = prevzal.id JOIN pracovnik ON pracovnik.id = prevzal.id_pracovnika WHERE (pracovnik.idzarizeni IS NOT NULL) AND (start BETWEEN date(now()) AND now() OR konec BETWEEN date(now()) AND now() OR (start < now() AND konec is null))");

    }



    public function getNahradniDilyByStroj($stroj)
    {
        return $this->em->getRepository(NahradniDil::class)->findBy(['stroj' => $stroj]);
    }

    public function ulozitNahradniDil(NahradniDil $nahradniDil)
    {
        if ($nahradniDil->getId() === null)
            $this->em->persist($nahradniDil);
        $this->em->flush();
    }

    public function smazatNahradniDil(NahradniDil $nahradniDil)
    {
        $this->em->remove($nahradniDil);
        $this->em->flush();
    }
}