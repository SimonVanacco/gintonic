<?php

namespace App\Controller;

use App\Form\CocktailFilterType;
use App\Repository\CocktailRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(
        Request                       $request,
        CocktailRepository            $cocktailRepository,
        FilterBuilderUpdaterInterface $filterBuilderUpdater,
        RequestStack                  $requestStack,
    ): Response {

        $session = $requestStack->getSession();

        $filterBuilder = $cocktailRepository->createQueryBuilder('c');

        $form = $this->createForm(CocktailFilterType::class);

        if ($request->get('reset-filter') !== null) {
            $session->set('index_filter', '{}');
            return $this->redirectToRoute('index');
        }

        if ($request->get('cocktail_filter') !== null) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = json_encode($request->request->all());
                $session->set('index_filter', $data);
                return $this->redirectToRoute('index');
            }
        } else {
            $filterData = json_decode($session->get('index_filter', '{}'), true);
            $sessionRequest = $request->duplicate(null, $filterData);
            $sessionRequest->setMethod('POST');
            $form->handleRequest($sessionRequest);
        }

        $filterBuilderUpdater->addFilterConditions($form, $filterBuilder);

        return $this->renderForm('app/index.html.twig', [
            'cocktails' => $filterBuilder->getQuery()->execute(),
            'form' => $form,
        ]);
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
