<?php

namespace App\Repository;

use App\Entity\Order;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countAllToday(): int
    {
        $today = new DateTimeImmutable();

        $qb = $this->createQueryBuilder('o')
                   ->select('count(o.id)')
                   ->where('o.createdAt >= :start')
                   ->andWhere('o.createdAt < :end')
                   ->setParameter(':start', $today->format('Y-m-d'))
                   ->setParameter(':end', $today->modify('+1 day')->format('Y-m-d'));

        return $qb->getQuery()->getScalarResult()[0][1];
    }

}
