<?php

namespace App\Repository;

use App\Entity\Cocktail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cocktail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cocktail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cocktail[]    findAll()
 * @method Cocktail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocktailRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cocktail::class);
    }

    public function findByAutocomplete(string $q)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->andWhere($qb->expr()->like('e.name', ':param'))
            ->setParameter('param', '%' . $q . '%');

        return $qb->getQuery()->getResult();
    }

    public function countAllAvailable(): int
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('COUNT(e.id)')
           ->leftJoin('e.ingredients', 'ci')
           ->leftJoin('ci.ingredient', 'i1')
           ->leftJoin(
               'ci.ingredient',
               'i2',
               Join::WITH,
               $qb->expr()->andX(
                   $qb->expr()->eq('i2.id', 'ci.ingredient'),
                   $qb->expr()->eq('i2.isInStock', '1')
               )
           )
           ->groupBy('e.id')
           ->having('COUNT(i1.id) = COUNT(i2.id)');

        return count($qb->getQuery()->getResult());
    }

}
