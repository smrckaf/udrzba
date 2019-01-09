<?php
/**
 * Created by PhpStorm.
 * User: smrcka
 * Date: 05.01.2019
 * Time: 9:22
 */

namespace AppBundle\Repository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;


class PoruchaRepository extends EntityRepository
{
    public function findByVyreseneByPracovnikAndTyp($idPracovnika, $typ)
    {
        return $this->createQueryBuilder('porucha')
            ->join('porucha.prevzate', 'prevzate')
            ->join('prevzate.logyobsluhy', 'log')
            ->where('prevzate.idPracovnika = :idPracovnika')
            ->andWhere('log.typ = :typ')
            ->groupBy('porucha')
            ->setParameter('idPracovnika', $idPracovnika)
            ->setParameter('typ', $typ)
            ->getQuery()
            ->getResult();
    }

}







