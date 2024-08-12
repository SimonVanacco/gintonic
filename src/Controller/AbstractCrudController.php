<?php

namespace App\Controller;

use App\Service\FilterService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCrudController extends AbstractController
{

    protected string $entityClass;
    protected string $entityType;
    protected ?string $filterType = null;
    protected string $templatePrefix;
    protected string $routePrefix;

    protected array $sort = [];
    protected int $perPage = 10;

    protected EntityManagerInterface $em;
    protected FilterService $filterService;

    #[Required]
    public function injectServices(
        EntityManagerInterface $em,
        FilterService $filterService
    ): void {
        $this->em            = $em;
        $this->filterService = $filterService;
    }

    abstract protected function configure(): void;

    #[Route('/', methods: ['GET', 'POST'])]
    #[Route('/page/{page}', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, int $page = 1): Response
    {
        $this->configure();

        // Default query
        $qb = $this->em->createQueryBuilder()
                       ->select('e')
                       ->from($this->entityClass, 'e');

        // Sort query
        $this->sortQuery($qb);

        // Filter query
        if ($this->filterType) {
            $filterData = $this->filterService->createForm($this->filterType, $qb, $request);
            $qb         = $filterData->qb;
            if ($filterData->isSubmitted) {
                return $this->redirectToRoute($this->routePrefix . '_index');
            }
        }

        // Paginate query
        $paginationData = PaginationService::paginateQuery($qb, $page, $this->perPage);
        $qb             = $paginationData->qb;

        // Get results
        $entities = $qb
            ->getQuery()
            ->getResult();

        return $this->render($this->templatePrefix . '/index.html.twig', [
            'entities'       => $entities,
            'maxPages'       => $paginationData->maxPages,
            'count'          => $paginationData->count,
            'currentPage'    => $page,
            'templatePrefix' => $this->templatePrefix,
            'routePrefix'    => $this->routePrefix,
            'filterForm'     => $filterData->form ?? null,
        ]);
    }

    #[Route('/create')]
    public function createAction(Request $request): Response
    {
        $this->configure();

        $entity = new $this->entityClass();

        $form = $this->createForm($this->entityType, $entity, [
            'action' => $this->generateUrl($this->routePrefix . '_create'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $this->onSubmittedForm($form, $entity);
            $this->onCreateEntity($entity);
            $this->em->persist($entity);
            $this->em->flush();
            $this->addFlash('success', 'Création réussie !');

            return $this->redirectToRoute($this->routePrefix . '_edit', ['id' => $entity->getId()]);
        }

        return $this->render($this->templatePrefix . '/create.html.twig', [
            'entity'         => $entity,
            'form'           => $form->createView(),
            'routePrefix'    => $this->routePrefix,
            'templatePrefix' => $this->templatePrefix,
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function showAction(int $id, Request $request): Response
    {
        $this->configure();

        $entity = $this->em->getRepository($this->entityClass)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->templatePrefix . '/show.html.twig', [
            'entity'         => $entity,
            'routePrefix'    => $this->routePrefix,
            'templatePrefix' => $this->templatePrefix,
        ]);
    }

    #[Route('/{id}/edit')]
    public function editAction(int $id, Request $request): Response
    {
        $this->configure();

        $entity = $this->em->getRepository($this->entityClass)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->entityType, $entity, [
            'action' => $this->generateUrl($this->routePrefix . '_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->onSubmittedForm($form, $entity);
            $this->onUpdateEntity($entity);
            $this->em->flush();
            $this->addFlash('success', 'Modification effectuée !');
        }

        return $this->render($this->templatePrefix . '/edit.html.twig', [
            'form'           => $form->createView(),
            'entity'         => $entity,
            'routePrefix'    => $this->routePrefix,
            'templatePrefix' => $this->templatePrefix,
        ]);
    }

    #[Route('/{id}/delete')]
    public function deleteAction(int $id): Response
    {
        $this->configure();
        $entity = $this->em->getRepository($this->entityClass)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $deleteResponse = $this->onDeleteEntity($entity);
        if ($deleteResponse) {
            return $deleteResponse;
        }

        $this->em->remove($entity);
        $this->em->flush();
        $this->addFlash('success', 'Suppression effectuée !');

        return $this->redirectToRoute($this->routePrefix . '_index');
    }

    protected function onSubmittedForm($form, $entity): void
    {
    }

    /**
     * Fonction appelée avant le persist() d'une entité
     * Utile pour pré-remplir les champs
     */
    protected function onCreateEntity($entity): void
    {
    }

    /**
     * Fonction appelée avant la mise à jour d'une entité
     */
    protected function onUpdateEntity($entity): void
    {
    }

    /**
     * Fonction appelée avant la suppression d'une entité
     * Peut retourner une réponse qui sera utilisée et empêchera la suppression
     * Utile pour vérifier que l'entité est supprimable et afficher un message d'erreur
     */
    protected function onDeleteEntity($entity): ?Response
    {
        return null;
    }

    protected function sortQuery(QueryBuilder $qb): void
    {
        foreach ($this->sort as $key => $s) {
            $qb->addOrderBy('e.' . $key, $s);
        }
    }

}
