<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservations>
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }



    public function countGuestsForSlot(\DateTimeInterface $date, \DateTimeInterface $time): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.guests), 0)')
            ->andWhere('r.reservation_date = :date')
            ->andWhere('r.reservation_time = :time')
            // optionnel : filtrer les réservations annulées
            ->andWhere('r.status != :cancelled')
            ->setParameter('date', $date)
            ->setParameter('time', $time)
            ->setParameter('cancelled', 'cancelled')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findUpcomingReservations(): array
    {
        $today = new \DateTime('today');
        $nowTime = new \DateTime();

        return $this->createQueryBuilder('r')
            ->andWhere('r.status != :cancelled')
            ->andWhere('(r.reservation_date > :today OR (r.reservation_date = :today AND r.reservation_time >= :nowTime))')
            ->setParameter('cancelled', 'cancelled')
            ->setParameter('today', $today)
            ->setParameter('nowTime', $nowTime)
            ->orderBy('r.reservation_date', 'ASC')
            ->addOrderBy('r.reservation_time', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findPastOrCancelledReservations(): array
    {
        $today = new \DateTime('today');
        $nowTime = new \DateTime();

        return $this->createQueryBuilder('r')
            ->andWhere('
            r.status = :cancelled
            OR (
                r.status != :cancelled
                AND (
                    r.reservation_date < :today
                    OR (r.reservation_date = :today AND r.reservation_time < :nowTime)
                )
            )
        ')
            ->setParameter('cancelled', 'cancelled')
            ->setParameter('today', $today)
            ->setParameter('nowTime', $nowTime)
            ->orderBy('r.reservation_date', 'DESC')
            ->addOrderBy('r.reservation_time', 'DESC')
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Reservations[] Returns an array of Reservations objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservations
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
