<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient')]
class IngredientController extends AbstractController {

    #[Route('/autocomplete')]
    public function autocomplete(Request $request, IngredientRepository $repository): Response {
        return $this->render('_partials/_autocomplete.html.twig', [
            'entities' => $repository->findByAutocomplete($request->get('q', '')),
        ]);
    }

}