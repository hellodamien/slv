<?php

namespace App\Repository;

use App\DTO\HomeVehicleSearch;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function findMostReserved(int $limit = 3): array
    {
        $query =
        '
            SELECT v.*
            FROM vehicle v
            LEFT JOIN reservation r ON r.vehicle_id = v.id
            GROUP BY v.id
            ORDER BY COUNT(r.id) DESC
            LIMIT '.$limit.'
        ';
        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    public function findAvailable(HomeVehicleSearch $dto): array
    {
//        $query =
//        '
//            SELECT v.*
//            FROM vehicle v
//            LEFT JOIN reservation r ON r.vehicle_id = v.id
//            WHERE r.start_date > :startDate
//            AND r.end_date < :endDate
//            AND v.type_id = :typeId
//            GROUP BY v.id
//        ';
//        return $this->getEntityManager()->getConnection()->executeQuery($query,
//        [
//            'startDate' => $dto->startDate->format('Y-m-d H:i:s'),
//            'endDate' => $dto->endDate->format('Y-m-d H:i:s'),
//            'typeId' => $dto->type->getId(),
//        ]
//        )->fetchAllAssociative();
        return
            $this->createQueryBuilder('v')
            ->leftJoin('v.reservations', 'r', Join::WITH, 'r.startDate > :startDate AND r.endDate < :endDate')
            ->andWhere('v.type = :type')
            ->setParameter('startDate', $dto->startDate)
            ->setParameter('endDate', $dto->endDate)
            ->setParameter('type', $dto->type)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Vehicle[] Returns an array of Vehicle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicle
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
