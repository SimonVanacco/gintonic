<?php

namespace App\Controller\Admin;

use App\Entity\Cocktail;
use App\Entity\CocktailIngredient;
use App\Form\CocktailIngredientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/cocktail')]
class CocktailIngredientAdminController extends AbstractController {

    #[Route('/{cocktail}/ingredient/', name: 'cocktail_ingredient_admin_index', methods: ['GET'])]
    public function index(Cocktail $cocktail): Response {
        return $this->render('admin/cocktail_ingredient/index.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }

    #[Route('/{cocktail}/ingredient/new', name: 'cocktail_ingredient_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Cocktail $cocktail, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(CocktailIngredientType::class, null, [
            'action' => $this->generateUrl('cocktail_ingredient_admin_new', ['cocktail' => $cocktail->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $cocktail->addIngredient($entity);
            $entityManager->persist($entity);
            $entityManager->flush();
            return $this->redirectToRoute('cocktail_ingredient_admin_index', ['cocktail' => $cocktail->getId()]);
        }

        return $this->renderForm('admin/cocktail_ingredient/create.html.twig', [
            'cocktail' => $cocktail,
            'form' => $form,
        ]);
    }

    #[Route('/{cocktail}/ingredient/{id}/edit', name: 'cocktail_ingredient_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cocktail $cocktail, int $id, EntityManagerInterface $entityManager): Response {
        $cocktailIngredient = $entityManager->find(CocktailIngredient::class, $id);
        if (!$cocktailIngredient || !$cocktail->getIngredients()->contains($cocktailIngredient)) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(CocktailIngredientType::class, $cocktailIngredient, [
            'action' => $this->generateUrl('cocktail_ingredient_admin_edit', ['cocktail' => $cocktail->getId(), 'id' => $id])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('cocktail_ingredient_admin_index', ['cocktail' => $cocktail->getId()]);
        }

        return $this->renderForm('admin/cocktail_ingredient/edit.html.twig', [
            'cocktail' => $cocktail,
            'cocktailIngredient' => $cocktailIngredient,
            'form' => $form,
        ]);
    }

    #[Route('/{cocktail}/ingredient/{id}/delete', name: 'cocktail_ingredient_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Cocktail $cocktail, int $id, EntityManagerInterface $entityManager): Response {
        $cocktailIngredient = $entityManager->find(CocktailIngredient::class, $id);
        if (!$cocktailIngredient || !$cocktail->getIngredients()->contains($cocktailIngredient)) {
            throw new NotFoundHttpException();
        }
        if ($this->isCsrfTokenValid('delete' . $cocktailIngredient->getId(), $request->request->get('_token'))) {
            $cocktail->getIngredients()->removeElement($cocktailIngredient);
            $entityManager->remove($cocktailIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cocktail_ingredient_admin_index', ['cocktail' => $cocktail->getId()]);
    }

}
