<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
    public function findMoviesByUser(string $id)
    {
        return $this->createQueryBuilder('u')
            ->select('m.name','m.thumb' )
            ->innerJoin('u.movies', 'm')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getArrayResult();
    }
}
