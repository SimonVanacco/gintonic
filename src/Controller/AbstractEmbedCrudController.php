<?php

namespace App\Controller;

use App\Entity\Interfaces\EmbedableEntity;
use App\Security\Voter\AbstractCrudVoter;
use App\Service\FilterService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractEmbedCrudController extends AbstractController
{

    protected string $parentEntityClass;
    protected string $entityClass;
    protected string $entityType;
    protected ?string $filterType = null;
    protected string $templatePrefix;
    protected string $routePrefix;

    protected string $formRequestType = 'modal'; // modal ou classic

    protected array $sort = [];

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

    #[Route('/{parentId}', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, int $parentId): Response
    {
        $this->configure();

        $parent = $this->em->find($this->parentEntityClass, $parentId);
        if (!$parent) {
            throw $this->createNotFoundException();
        }
        if (!$this->isGranted(AbstractCrudVoter::EDIT, $parent)) {
            throw $this->createAccessDeniedException();
        }
        if (!class_implements($this->entityClass, EmbedableEntity::class)) {
            throw new InvalidArgumentException(
                "L'entité $this->entityClass doit implémenter l'interface EmbedableEntity"
            );
        }

        $page = $request->query->getInt('page', 1);

        // Default query
        $qb = $this->em->createQueryBuilder()
                       ->select('e')
                       ->from($this->entityClass, 'e')
                       ->andWhere(
                           'e.' . $this->entityClass::getParentRelationship($this->parentEntityClass) . ' = :parent'
                       )
                       ->setParameter('parent', $parent);

        // Sort query
        foreach ($this->sort as $key => $s) {
            $qb->addOrderBy('e.' . $key, $s);
        }

        // Filter query
        if ($this->filterType) {
            $filterData = $this->filterService->createForm($this->filterType, $qb, $request);
            $qb         = $filterData->qb;
            if ($filterData->isSubmitted) {
                return $this->redirectToRoute($this->routePrefix . '_index');
            }
        }

        // Paginate query
        $paginationData = PaginationService::paginateQuery($qb, $page);
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
            'parent'         => $parent,
        ]);
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

}
