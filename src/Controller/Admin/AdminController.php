<?php

namespace App\Controller\Admin;

use App\Repository\CocktailRepository;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController {

    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    #[Route('/', name: 'admin_index', methods: ['GET'])]
    public function index(): Response {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/widget/shopping', name: 'admin_widget_shopping', methods: ['GET'])]
    public function shoppingListWidget(IngredientRepository $repository) {
        $ingredients = $repository->findBy(['isToBuy' => true]);
        return $this->render('admin/widgets/_shopping_list.html.twig', ['ingredients' => $ingredients]);
    }

    #[Route('/widget/cocktails', name: 'admin_widget_cocktails', methods: ['GET'])]
    public function cocktailsCountWidget(CocktailRepository $repository) {
        $count = $repository->count([]);
        return $this->render('admin/widgets/_simple_count.html.twig', ['label' => $this->translator->trans('Cocktails'), 'count' => $count, 'icon' => 'fas fa-martini-glass']);
    }

    #[Route('/widget/cocktails_available', name: 'admin_widget_cocktails_available', methods: ['GET'])]
    public function availableCocktailsCountWidget(CocktailRepository $repository) {
        $count = $repository->countAllAvailable();
        return $this->render('admin/widgets/_simple_count.html.twig', ['label' => $this->translator->trans('Available cocktails'), 'count' => $count, 'icon' => 'fas fa-martini-glass']);
    }

    #[Route('/widget/ingredients', name: 'admin_widget_ingredients', methods: ['GET'])]
    public function ingredientsCountWidget(IngredientRepository $repository) {
        $count = $repository->count([]);
        return $this->render('admin/widgets/_simple_count.html.twig', ['label' => $this->translator->trans('Ingredients'), 'count' => $count, 'icon' => 'fas fa-wine-bottle']);
    }

    #[Route('/widget/ingredients_available', name: 'admin_widget_ingredients_available', methods: ['GET'])]
    public function availableIngredientsCountWidget(Request $request, IngredientRepository $repository) {
        $count = $repository->count(['isInStock' => true]);
        return $this->render('admin/widgets/_simple_count.html.twig', ['label' => $this->translator->trans('Available ingredients'), 'count' => $count, 'icon' => 'fas fa-wine-bottle']);
    }
}