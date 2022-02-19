<?php

namespace App\Controller;

use App\Entity\Cocktail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cocktail')]
class CocktailController extends AbstractController {

    #[Route('/{id}')]
    public function show(Cocktail $cocktail): Response {
        return $this->render('app/cocktail/show.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }

    #[Route('/autocomplete')]
    public function autocomplete(Cocktail $cocktail): Response {
        return $this->render('app/cocktail/show.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }

}
