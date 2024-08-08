<?php

namespace App\Controller\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BooleanController extends AbstractController
{

    #[Route('/utils/boolean_toggle', name: 'utils_boolean_toggle', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function toggle(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data        = json_decode($request->getContent(), true);
        $raw_payload = $data['payload'];
        $payload     = json_decode(base64_decode($raw_payload), true);

        $entity     = $em->find($payload['class'], $payload['id']);
        $setterName = "set" . ucfirst($payload['property']);
        $getterName = "get" . ucfirst($payload['property']);
        $entity->$setterName(!$entity->$getterName());
        $em->flush();

        return new JsonResponse();
    }

    #[Route('/utils/boolean_set', name: 'utils_boolean_set', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function set(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data        = json_decode($request->getContent(), true);
        $raw_payload = $data['payload'];
        $value       = boolval($data['value']);
        $payload     = json_decode(base64_decode($raw_payload), true);

        $entity     = $em->find($payload['class'], $payload['id']);
        $setterName = "set" . ucfirst($payload['property']);
        $entity->$setterName($value);
        $em->flush();

        return new JsonResponse();
    }

}
