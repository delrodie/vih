<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findList()
    {
        return $this->createQueryBuilder('u')
            ->where('u.username <> :user')
            ->orderBy('u.username', 'ASC')
            ->setParameter('user', 'delrodie')
            ->getQuery()->getResult()
            ;
    }

    public function findUser()
    {
        return $this->createQueryBuilder('u')
                    ->where('u.username <> :user')
                    ->orderBy('u.username', 'ASC')
                    ->setParameter('user', 'delrodie')
            ;
    }

}
