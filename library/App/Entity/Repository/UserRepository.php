<?php

namespace App\Entity\Repository;

use Doctrine\ORM;

class UserRepository extends ORM\EntityRepository
{
    public function findByTwitterHandle($handle)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere('u.twitterHandle = ?1');
        $qb->setParameter(1, $handle);
        
        return $qb->getQuery()->getSingleResult();
    }
}