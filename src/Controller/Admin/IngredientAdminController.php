<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use App\Service\IngredientFileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ingredient')]
class IngredientAdminController extends AbstractController {

    #[Route('/', name: 'ingredient_admin_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): Response {
        return $this->render('admin/ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'ingredient_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, IngredientFileUploader $fileUploader): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $newFilename = $fileUploader->upload($photoFile);
                $ingredient->setPhoto($newFilename);
            }

            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ingredient_admin_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response {
        return $this->render('admin/ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'ingredient_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager, IngredientFileUploader $fileUploader): Response {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $newFilename = $fileUploader->upload($photoFile);
                $ingredient->setPhoto($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('ingredient_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ingredient_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete' . $ingredient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ingredient_admin_index', [], Response::HTTP_SEE_OTHER);
    }

}
