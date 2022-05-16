<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order')]
class OrderAdminController extends AbstractController {

    #[Route('/', name: 'order_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response {
        $orders = $entityManager
            ->getRepository(Order::class)
            ->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}', name: 'order_admin_show', methods: ['GET'])]
    public function show(Order $order): Response {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }
}
