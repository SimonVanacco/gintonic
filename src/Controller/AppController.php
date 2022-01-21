<?php

namespace App\Controller;

use App\Repository\CocktailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(CocktailRepository $cocktailRepository): Response {

        $cocktails = $cocktailRepository->findAll();

        return $this->render('app/index.html.twig', ['cocktails' => $cocktails]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('app/login.html.twig', ['error' => $error, 'last_username' => $lastUsername]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void {
        // Empty
    }


}
