<?php

namespace App\Repository;

use App\Entity\Recommendation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recommendation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recommendation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recommendation[]    findAll()
 * @method Recommendation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommendation::class);
    }

     /**
      * @return Recommendation[]
      */
    public function findByUserAndDate(User $user, \DateTimeInterface $dateTime): array
    {
        $dateFrom = $dateTime->format("Y-m-d") . " 00:00:00";
        $dateTo = $dateTime->format("Y-m-d") . " 23:59:59";

        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :userId')
            ->andWhere('r.created_at >= :dateFrom')
            ->andWhere('r.created_at <= :dateTo')
            ->setParameter('userId', $user->getId())
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
