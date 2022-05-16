<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 */
class OrderItem {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private Order $parent;

    /**
     * @ORM\ManyToOne(targetEntity=Cocktail::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Cocktail $cocktail;

    public function getId(): ?int {
        return $this->id;
    }

    public function getParent(): ?Order {
        return $this->parent;
    }

    public function setParent(?Order $parent): self {
        $this->parent = $parent;

        return $this;
    }

    public function getCocktail(): ?Cocktail {
        return $this->cocktail;
    }

    public function setCocktail(?Cocktail $cocktail): self {
        $this->cocktail = $cocktail;

        return $this;
    }
}
