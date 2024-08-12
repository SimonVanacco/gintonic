<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Doctrine\ORM\QueryBuilder;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function findByAutocomplete(string $q)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->andWhere($qb->expr()->like('e.name', ':param'))
            ->setParameter('param', '%' . $q . '%');

        return $qb->getQuery()->getResult();
    }

}
