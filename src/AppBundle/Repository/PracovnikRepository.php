<?php
/**
 * Created by PhpStorm.
 * User: Jáchym
 * Date: 02.12.2018
 * Time: 15:45
 */

namespace AppBundle\Entity;


namespace AppBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class PracovnikRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($login)
    {
        return $this->createQueryBuilder('p')
            ->where('p.login = :login OR p.email = :email')
            ->setParameter('login', $login)
            ->setParameter('email', $login)
            ->getQuery()
            ->getOneOrNullResult();
    }
}