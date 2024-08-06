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

    #[Route('/', name: 'admin_config_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ConfigService $configService): Response
    {
        $form = $this->createForm(MainConfigType::class, $configService->getConfig());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                if (str_starts_with($key, '_')) {
                    continue;
                }
                $configService->setConfigKey($key, $value);
            }
        }

        return $this->render('admin/config/index.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/appearance', name: 'admin_config_appearance', methods: ['GET', 'POST'])]
    public function appearance(Request $request, ConfigService $configService, StyleService $styleService): Response
    {
        $form = $this->createForm(AppearanceConfigType::class, $configService->getConfig());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                if (str_starts_with($key, '_')) {
                    continue;
                }
                $configService->setConfigKey($key, $value);
            }

            $styleService->recompileSass();
        }

        return $this->render('admin/config/appearance.html.twig', ['form' => $form->createView()]);
    }

}
