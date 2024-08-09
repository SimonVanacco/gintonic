<?php

namespace App\Controller\Admin;

use App\Form\Config\AppearanceConfigType;
use App\Form\Config\MainConfigType;
use App\Service\ConfigService;
use App\Service\StyleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/config')]
class ConfigController extends AbstractController
{

    public function __construct(
        private readonly ConfigService $configService,
    ) {
    }

    #[Route('/', name: 'admin_config_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(MainConfigType::class, $this->configService->getConfig());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->configService->handleConfigFormSubmit($form);
        }

        return $this->render('admin/config/index.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/appearance', name: 'admin_config_appearance', methods: ['GET', 'POST'])]
    public function appearance(
        Request $request,
        StyleService $styleService
    ): Response {
        $form = $this->createForm(AppearanceConfigType::class, $this->configService->getConfig());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->configService->handleConfigFormSubmit($form);
            $styleService->recompileSass();
        }

        return $this->render('admin/config/appearance.html.twig', ['form' => $form->createView()]);
    }

}
