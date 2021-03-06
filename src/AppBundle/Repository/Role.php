<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Role
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Role extends EntityRepository
{

    public function takeRole($user)
    {
        $query = $this->createQueryBuilder('r')

            ->select(
                'r.id',
                'r.role',
                'u.id',
                'u.username'
            )
            // ->from('AppBundle:Post', 'p')
            ->innerJoin('r.users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $user)
            ->getQuery();

        return $query->getResult();

    }
}
