<?php

namespace App\Controller;

use App\Entity\Glass;
use App\Form\GlassType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/glass')]
class GlassAdminController extends AbstractController {

    #[Route('/', name: 'glass_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response {
        $glasses = $entityManager
            ->getRepository(Glass::class)
            ->findAll();

        return $this->render('admin/glass/index.html.twig', [
            'glasses' => $glasses,
        ]);
    }

    #[Route('/new', name: 'glass_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $glass = new Glass();
        $form = $this->createForm(GlassType::class, $glass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($glass);
            $entityManager->flush();

            return $this->redirectToRoute('glass_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/glass/new.html.twig', [
            'glass' => $glass,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'glass_admin_show', methods: ['GET'])]
    public function show(Glass $glass): Response {
        return $this->render('admin/glass/show.html.twig', [
            'glass' => $glass,
        ]);
    }

    #[Route('/{id}/edit', name: 'glass_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Glass $glass, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(GlassType::class, $glass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('glass_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/glass/edit.html.twig', [
            'glass' => $glass,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'glass_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Glass $glass, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete' . $glass->getId(), $request->request->get('_token'))) {
            $entityManager->remove($glass);
            $entityManager->flush();
        }

        return $this->redirectToRoute('glass_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
