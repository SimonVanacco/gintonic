<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Form\CocktailType;
use App\Repository\CocktailRepository;
use App\Service\CocktailFileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/cocktail')]
class CocktailIngredientAdminController extends AbstractController {

    #[Route('/{cocktail}/ingredient/', name: 'cocktail_ingredient_admin_index', methods: ['GET'])]
    public function index(Cocktail $cocktail): Response {
        return $this->render('admin/cocktail_ingredient/index.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }

//    #[Route('/new', name: 'cocktail_admin_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager, CocktailFileUploader $fileUploader): Response {
//        $cocktail = new Cocktail();
//        $form = $this->createForm(CocktailType::class, $cocktail);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $photoFile = $form->get('photo')->getData();
//            if ($photoFile) {
//                $newFilename = $fileUploader->upload($photoFile);
//                $cocktail->setPhoto($newFilename);
//            }
//
//            $entityManager->persist($cocktail);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('cocktail_admin_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('admin/cocktail/new.html.twig', [
//            'cocktail' => $cocktail,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'cocktail_admin_show', methods: ['GET'])]
//    public function show(Cocktail $cocktail): Response {
//        return $this->render('admin/cocktail/show.html.twig', [
//            'cocktail' => $cocktail,
//        ]);
//    }
//
//    #[Route('/{id}/edit', name: 'cocktail_admin_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Cocktail $cocktail, EntityManagerInterface $entityManager, CocktailFileUploader $fileUploader): Response {
//        $form = $this->createForm(CocktailType::class, $cocktail);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $photoFile = $form->get('photo')->getData();
//            if ($photoFile) {
//                $newFilename = $fileUploader->upload($photoFile);
//                $cocktail->setPhoto($newFilename);
//            }
//
//            $entityManager->flush();
//
//            return $this->redirectToRoute('cocktail_admin_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('admin/cocktail/edit.html.twig', [
//            'cocktail' => $cocktail,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'cocktail_admin_delete', methods: ['POST'])]
//    public function delete(Request $request, Cocktail $cocktail, EntityManagerInterface $entityManager): Response {
//        if ($this->isCsrfTokenValid('delete' . $cocktail->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($cocktail);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('cocktail_admin_index', [], Response::HTTP_SEE_OTHER);
//    }

}
