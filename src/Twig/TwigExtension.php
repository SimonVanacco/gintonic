<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{

    public function __construct()
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('boolean_payload', $this->booleanPayload(...)),
        ];
    }

    /**
     * Creates the payload for the Boolean Utility
     *
     *
     * @return string
     * @todo Crypt returned string ?
     */
    public function booleanPayload(mixed $entity, string $property): string
    {
        return base64_encode(
            json_encode(
                [
                    'class'    => $entity::class,
                    'id'       => $entity->getId(),
                    'property' => $property,
                ]
            )
        );
    }

}
