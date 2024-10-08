<?php

namespace App\Form;

use App\Entity\CocktailIngredient;
use App\Form\Type\IngredientAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CocktailIngredientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', IngredientAutocompleteType::class)
            ->add('quantity')
            ->add('unit')
            ->add('isDecoration')
            ->add('isOptional');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CocktailIngredient::class,
        ]);
    }
}
