<?php

namespace App\Repository;

use App\Entity\Cocktail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cocktail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cocktail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cocktail[]    findAll()
 * @method Cocktail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocktailRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Cocktail::class);
    }

}
