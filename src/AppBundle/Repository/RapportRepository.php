<?php

namespace AppBundle\Repository;

/**
 * RapportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RapportRepository extends \Doctrine\ORM\EntityRepository
{
    public function findList()
    {
        return $this->createQueryBuilder('r')
                    ->where('r.statut = 1')
                    ->orderBy('r.date', 'DESC')
                    ->getQuery()->getResult()
            ;
    }
}