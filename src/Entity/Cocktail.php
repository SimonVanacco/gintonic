<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CocktailRepository")
 */
class Cocktail {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = '';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $recipe;

    /**
     * @ORM\ManyToOne(targetEntity=Glass::class)
     */
    private ?Glass $glass;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $isEnabled = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $isFeatured = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $photo;

    /**
     * @ORM\OneToMany(targetEntity=CocktailIngredient::class, mappedBy="cocktail", orphanRemoval=true)
     */
    private Collection $ingredients;

    /**
     * @ORM\ManyToMany(targetEntity=CocktailTag::class, mappedBy="cocktails")
     */
    private Collection $tags;

    public function __toString(): string {
        return $this->name;
    }

    public function __construct() {
        $this->ingredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getRecipe(): ?string {
        return $this->recipe;
    }

    public function setRecipe(?string $recipe): self {
        $this->recipe = $recipe;

        return $this;
    }

    public function getGlass(): ?Glass {
        return $this->glass;
    }

    public function setGlass(?Glass $glass): self {
        $this->glass = $glass;

        return $this;
    }

    public function getIsEnabled(): ?bool {
        return $this->isEnabled;
    }

    public function setIsEnabled(?bool $isEnabled): self {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getIsFeatured(): ?bool {
        return $this->isFeatured;
    }

    public function setIsFeatured(?bool $isFeatured): self {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getLevel(): ?int {
        return $this->level;
    }

    public function setLevel(?int $level): self {
        $this->level = $level;

        return $this;
    }

    /**
     * @return CocktailIngredient[]|Collection
     */
    public function getIngredients(): array|Collection {
        return $this->ingredients;
    }

    public function addIngredient(CocktailIngredient $ingredient): self {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setCocktail($this);
        }

        return $this;
    }

    public function removeIngredient(CocktailIngredient $ingredient): self {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getCocktail() === $this) {
                $ingredient->setCocktail(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CocktailTag[]
     */
    public function getTags(): array|Collection {
        return $this->tags;
    }

    public function addTag(CocktailTag $tag): self {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addCocktail($this);
        }

        return $this;
    }

    public function removeTag(CocktailTag $tag): self {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeCocktail($this);
        }

        return $this;
    }


    public function getPhoto(): ?string {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self {
        $this->photo = $photo;

        return $this;
    }
}
