<?php

namespace App\Service;

use App\Model\FilterData;
use Doctrine\ORM\QueryBuilder;
use Spiriit\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class FilterService
{

    public function __construct(
        private FilterBuilderUpdaterInterface $filterBuilderUpdater,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function createForm(string $filterType, QueryBuilder $qb, Request $request): FilterData
    {
        $form = $this->formFactory
            ->create($filterType)
            ->handleRequest($request);

        if ($request->query->has('reset-filter')) {
            $request->getSession()->set($form->getName(), []);

            return new FilterData($form, $qb);
        }

        // Le form de filtres est soumis et valide, on s'en occupe
        if ($form->isSubmitted() && $form->isValid()) {
            $filterData = $form->getData();
            $request->getSession()->set($form->getName(), $filterData);
            $this->filterBuilderUpdater->addFilterConditions($form, $qb);

            $request->getSession()->set($form->getName(), $request->get($form->getName()));

            return new FilterData($form, $qb, true);
        }

        // Aucune info de session pour ce form, pas de filtration à effectuer
        if (!$request->getSession()->has($form->getName())) {
            return new FilterData($form, $qb);
        }

        // On utilise les infos en session pour filtrer
        $form->submit($request->getSession()->get($form->getName()));
        $this->filterBuilderUpdater->addFilterConditions($form, $qb);

        return new FilterData($form, $qb, isFiltered: true);
    }

}
