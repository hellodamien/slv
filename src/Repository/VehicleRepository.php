<?php

namespace App\Repository;

use App\DTO\HomeVehicleSearch;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findMostReserved(int $itemsPerPage, int $page): array
    {
        $query =
        '
            SELECT v.*
            FROM vehicle v
            LEFT JOIN reservation r ON r.vehicle_id = v.id
            GROUP BY v.id
            ORDER BY COUNT(r.id) DESC
            LIMIT '.$itemsPerPage.' OFFSET '.$page * $itemsPerPage.'
        ';
        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    public function findAvailable(HomeVehicleSearch $dto): array
    {
        if ($dto->type === null) {
            $typeId = 0;
        } else {
            $typeId = $dto->type->getId();
        }
        // get all vehicles that are not reserved in the given time frame
        // @fixme i dont get it help me :c
        $query = '
            SELECT v.*
            FROM vehicle v
            WHERE v.id NOT IN
            (
                SELECT r.vehicle_id
                FROM reservation r
                WHERE r.start_date >= :startDate
                OR r.end_date <= :endDate
            )
            AND v.type_id = :typeId OR :typeId = 0
            LIMIT '.$dto->itemsPerPage.' OFFSET '.$dto->page * $dto->itemsPerPage.'
        ';

        return $this->getEntityManager()->getConnection()->executeQuery($query,
        [
            'startDate' => $dto->startDate->format('Y-m-d H:i:s'),
            'endDate'   => $dto->endDate->format('Y-m-d H:i:s'),
            'typeId'    => $typeId,
        ]
        )->fetchAllAssociative();
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
