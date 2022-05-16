<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\PublicOrderType;
use App\Repository\CocktailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/cocktail')]
class CocktailController extends AbstractController {

    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

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

    #[Route('/{id}/order')]
    public function order(Request $request, EntityManagerInterface $em, Cocktail $cocktail): Response {

        $session = $request->getSession();

        $order = new Order();
        $order->setName($session->get('order_name', ''));

        $form = $this->createForm(PublicOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Order $order */
            $order = $form->getData();
            $item = new OrderItem();
            $item->setCocktail($cocktail);
            $order->addItem($item);
            $em->persist($order);
            $em->flush();
            $session->set('order_name', $order->getName());
            $this->addFlash('success', [$this->translator->trans('Your order has been received ! '), $this->translator->trans('Please wait while your cocktail is being made')]);
            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('app/cocktail/order.html.twig', [
            'cocktail' => $cocktail,
            'form' => $form->createView()
        ]);
    }


}
