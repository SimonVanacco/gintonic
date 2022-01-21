<?php

namespace App\Controller;

use App\Entity\Cocktail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CocktailController extends AbstractController
{
    #[Route('/cocktail/{id}')]
    public function show(Cocktail $cocktail): Response
    {
        return $this->render('cocktail/show.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }
}
