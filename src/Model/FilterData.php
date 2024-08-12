<?php

namespace App\Model;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

/**
 * Objet de transfert des données relatives au filtre d'un crud
 */
readonly class FilterData
{

    public function __construct
    (
        public FormInterface $form,
        public QueryBuilder $qb,
        public bool $isSubmitted = false,
        public bool $isFiltered = false
    ) {
    }

}
