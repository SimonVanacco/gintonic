<?php

namespace App\Controller;

use App\Form\CocktailFilterType;
use App\Repository\CocktailRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(Request $request, CocktailRepository $cocktailRepository, FilterBuilderUpdaterInterface $filterBuilderUpdater): Response {

        $filterBuilder = $cocktailRepository->createQueryBuilder('c');

        $form = $this->createForm(CocktailFilterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filterBuilderUpdater->addFilterConditions($form, $filterBuilder);
        }

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

    protected function renderForm(string $view, array $parameters = [], Response $response = null): Response {
        if (null === $response) {
            $response = new Response();
        }

        foreach ($parameters as $k => $v) {
            if ($v instanceof FormView) {
                throw new \LogicException(sprintf('Passing a FormView to "%s::renderForm()" is not supported, pass directly the form instead for parameter "%s".', get_debug_type($this), $k));
            }

            if (!$v instanceof FormInterface) {
                continue;
            }

            $parameters[$k] = $v->createView();

            if (200 === $response->getStatusCode() && $v->isSubmitted() && !$v->isValid()) {
                $response->setStatusCode(422);
            } else {
                $response->setStatusCode(303);
            }
        }

        return $this->render($view, $parameters, $response);
    }

}
