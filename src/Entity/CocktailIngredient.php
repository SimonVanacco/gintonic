<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CocktailIngredient
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Cocktail::class, inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cocktail $cocktail;

    #[ORM\ManyToOne(targetEntity: Ingredient::class, inversedBy: 'cocktails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isDecoration = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isOptional = false;

    #[ORM\ManyToOne(targetEntity: Unit::class)]
    private ?Unit $unit;

    public function getFormattedQuantity(): ?string
    {
        if (!$this->unit) {
            return strval($this->quantity);
        }
        $unitLabel = ($this->quantity > 1) ? $this->unit->getPlural() : $this->unit->getSingular();

        return $this->quantity . " " . $unitLabel;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCocktail(): ?Cocktail
    {
        return $this->cocktail;
    }

    public function setCocktail(?Cocktail $cocktail): self
    {
        $this->cocktail = $cocktail;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIsDecoration(): ?bool
    {
        return $this->isDecoration;
    }

    public function setIsDecoration(?bool $isDecoration): self
    {
        $this->isDecoration = $isDecoration;

        return $this;
    }

    public function getIsOptional(): ?bool
    {
        return $this->isOptional;
    }

    public function setIsOptional(?bool $isOptional): self
    {
        $this->isOptional = $isOptional;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}
