<?php

namespace App\Controller\Admin;

use App\Controller\AbstractCrudController;
use App\Entity\Order;
use App\Form\OrderFilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/order')]
#[IsGranted('ROLE_ADMIN')]
class OrderAdminController extends AbstractCrudController
{

    protected function configure(): void
    {
        $this->entityClass    = Order::class;
        $this->filterType     = OrderFilterType::class;
        $this->templatePrefix = 'admin/order/';
        $this->routePrefix    = 'app_admin_orderadmin';
        $this->sort           = ['createdAt' => 'DESC'];
        $this->perPage        = 20;
    }

    #[Route('/create')]
    public function createAction(Request $request): Response
    {
        return $this->redirectToRoute('app_admin_order_index');
    }

    #[Route('/{id}/edit')]
    public function editAction(int $id, Request $request): Response
    {
        return $this->redirectToRoute('app_admin_order_index');
    }

    #[Route('/{id}/delete')]
    public function deleteAction(int $id): Response
    {
        return $this->redirectToRoute('app_admin_order_index');
    }


}
