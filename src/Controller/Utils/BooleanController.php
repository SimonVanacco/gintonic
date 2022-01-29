<?php

namespace App\Controller\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BooleanController extends AbstractController {

    #[Route('/utils/boolean_toggle', name: 'utils_boolean_toggle', methods: ['PUT'])]
    public function toggleAction(Request $request, EntityManagerInterface $em) {


        $data = json_decode($request->getContent(), true);
        $raw_payload = $data['payload'];
        $payload = json_decode(base64_decode($raw_payload), true);

        $entity = $em->find($payload['class'], $payload['id']);
        $setterName = "set" . ucfirst($payload['property']);
        $getterName = "get" . ucfirst($payload['property']);
        $entity->$setterName(!$entity->$getterName());
        $em->flush();

        return new JsonResponse();

    }

}