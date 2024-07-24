<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\PublicOrderType;
use App\Repository\CocktailRepository;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/cocktail')]
class CocktailController extends AbstractController
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/autocomplete')]
    public function autocomplete(Request $request, CocktailRepository $repository): Response
    {
        return $this->render('_partials/_autocomplete.html.twig', [
            'entities' => $repository->findByAutocomplete($request->get('q', '')),
        ]);
    }

    #[Route('/{id}')]
    public function show(Cocktail $cocktail): Response
    {
        return $this->render('app/cocktail/show.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }

    #[Route('/{id}/order')]
    public function order(
        Request $request,
        EntityManagerInterface $em,
        ConfigService $configService,
        MailerInterface $mailer,
        TexterInterface $texter,
        Cocktail $cocktail,
    ): Response {
        $session = $request->getSession();

        if (!$configService->getConfigItem('ordersOpen')) {
            return $this->redirectToRoute('index');
        }

        $order = new Order();
        $order->setName($session->get('order_name', ''));

        $form = $this->createForm(PublicOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Order $order */
            $order = $form->getData();
            $item  = new OrderItem();
            $item->setCocktail($cocktail);
            $order->addItem($item);
            $em->persist($order);
            $em->flush();
            $session->set('order_name', $order->getName());
            $this->addFlash(
                'success',
                [
                    $this->translator->trans('Your order has been received ! '),
                    $this->translator->trans('Please wait while your cocktail is being made'),
                ]
            );



            try {
                $email = (new TemplatedEmail())
                    ->from($configService->getConfigItem('fromEmail'))
                    ->to($configService->getConfigItem('adminEmail'))
                    ->subject('[Gintonic] New Order')
                    ->htmlTemplate('emails/admin_order.html.twig')
                    ->context([
                        'order' => $order,
                    ]);
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
            }

            $smsNotificationTo = $configService->getConfigItem('smsNotificationTo');

            if ($smsNotificationTo) {
                try {
                    $sms = new SmsMessage(
                        $smsNotificationTo,
                        'New order on the GinTonic app for ' . $order->getName() . ' ! Please follow this link : ' . $this->generateUrl('order_admin_show', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                    );
                    $sentMessage = $texter->send($sms);
                } catch (\Symfony\Component\Notifier\Exception\TransportExceptionInterface $e) {
                }
            }

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/cocktail/order.html.twig', [
            'cocktail' => $cocktail,
            'form'     => $form->createView(),
        ]);
    }


}
