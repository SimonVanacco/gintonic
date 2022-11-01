<?php

namespace App\Form\Type;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class IngredientsAutocompleteType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'class' => Ingredient::class,
            'label' => 'Ingredients',
            'choice_label' => 'name',
            'multiple' => true,
        ]);
    }

    public function getParent(): string {
        return ParentEntityAutocompleteType::class;
    }

}