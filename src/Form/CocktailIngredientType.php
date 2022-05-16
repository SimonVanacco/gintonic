<?php

namespace App\Form;

use App\Entity\CocktailIngredient;
use App\Entity\Ingredient;
use App\Form\Type\AutocompleteEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CocktailIngredientType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('ingredient', AutocompleteEntityType::class, [
                'class' => Ingredient::class,
                'routeName' => 'app_ingredient_autocomplete',
            ])
            ->add('quantity')
            ->add('unit')
            ->add('isDecoration')
            ->add('isOptional')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => CocktailIngredient::class,
        ]);
    }
}
