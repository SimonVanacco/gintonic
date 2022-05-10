<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Repository\CocktailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cocktail')]
class CocktailController extends AbstractController {

    #[Route('/autocomplete')]
    public function autocomplete(Request $request, CocktailRepository $repository): Response {
        return $this->render('_partials/_autocomplete.html.twig', [
            'entities' => $repository->findByAutocomplete($request->get('q', '')),
        ]);
    }

    #[Route('/{id}')]
    public function show(Cocktail $cocktail): Response {
        return $this->render('app/cocktail/show.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }

}
