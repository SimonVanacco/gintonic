<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'boolean')]
    private bool $isInStock = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isToBuy = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo;

    #[ORM\ManyToOne(targetEntity: IngredientCategory::class)]
    private ?IngredientCategory $category;

    #[ORM\OneToMany(targetEntity: CocktailIngredient::class, mappedBy: 'ingredient', orphanRemoval: true)]
    private Collection $cocktails;

    public function __toString(): string
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->cocktails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsInStock(): ?bool
    {
        return $this->isInStock;
    }

    public function setIsInStock(?bool $isInStock): self
    {
        $this->isInStock = $isInStock;

        return $this;
    }

    public function getIsToBuy(): ?bool
    {
        return $this->isToBuy;
    }

    public function setIsToBuy(?bool $isToBuy): self
    {
        $this->isToBuy = $isToBuy;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCategory(): ?IngredientCategory
    {
        return $this->category;
    }

    public function setCategory(?IngredientCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|CocktailIngredient[]
     */
    public function getCocktails(): array|Collection
    {
        return $this->cocktails;
    }

    public function addCocktail(CocktailIngredient $cocktail): self
    {
        if (!$this->cocktails->contains($cocktail)) {
            $this->cocktails[] = $cocktail;
            $cocktail->setIngredient($this);
        }

        return $this;
    }

    public function removeCocktail(CocktailIngredient $cocktail): self
    {
        if ($this->cocktails->contains($cocktail)) {
            $this->cocktails->removeElement($cocktail);
            // set the owning side to null (unless already changed)
            if ($cocktail->getIngredient() === $this) {
                $cocktail->setIngredient(null);
            }
        }

        return $this;
    }
}
