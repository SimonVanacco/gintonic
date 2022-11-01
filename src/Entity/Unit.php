<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Unit {

    public const DEFAULT = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $singular;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $plural;

    public function __toString(): string {
        return $this->singular;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getSingular(): ?string {
        return $this->singular;
    }

    public function setSingular(string $singular): self {
        $this->singular = $singular;

        return $this;
    }

    public function getPlural(): ?string {
        return $this->plural;
    }

    public function setPlural(string $plural): self {
        $this->plural = $plural;

        return $this;
    }
}
