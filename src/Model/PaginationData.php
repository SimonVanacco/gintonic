<?php

namespace App\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * Objet de transfert des données relatives à la pagination crud
 */
readonly class PaginationData
{

    public function __construct
    (
        public QueryBuilder $qb,
        public int $maxPages,
        public int $count
    ) {
    }

}
