<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;


/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends EntityRepository
{
    public function findTopMovies()
    {
        return $this->createQueryBuilder('m')
            ->select('count(u.id) as cnt', 'm.name')
            ->innerJoin('m.users', 'u')
            ->addGroupBy('m.id')
            ->orderBy('cnt', 'DESC')
            ->getQuery()->getArrayResult();
    }
}
