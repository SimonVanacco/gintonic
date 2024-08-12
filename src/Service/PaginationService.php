<?php

namespace App\Service;

use App\Model\PaginationData;
use Doctrine\ORM\QueryBuilder;

class PaginationService
{

    public static function paginateQuery(QueryBuilder $qb, int $currentPage, int $perPage = 10): PaginationData
    {
        $countQb = clone $qb;
        $count   = $countQb->select('COUNT(e)')->getQuery()->getSingleScalarResult();

        if ($perPage === -1) {
            return new PaginationData($qb, 1, $count);
        }

        $maxPages = ceil($count / $perPage);

        if (!$maxPages) {
            $maxPages = 1;
        }

        $qb->setFirstResult(($currentPage - 1) * $perPage);
        $qb->setMaxResults($perPage);

        return new PaginationData($qb, $maxPages, $count);
    }

}
