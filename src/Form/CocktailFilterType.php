<?php

namespace App\Form;

use App\Entity\Cocktail;
use App\Entity\Glass;
use App\Entity\Ingredient;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CocktailFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', Filters\TextFilterType::class, [
                'required' => false,
            ])
            ->add('ingredients', Filters\EntityFilterType::class, [
                'class' => Ingredient::class,
                'mapped' => false,
                'required' => false,
            ])
            ->add('glass', Filters\EntityFilterType::class, [
                'class' => Glass::class,
                'required' => false,
            ])
            ->add('fake', HiddenType::class, [
                'mapped' => false,
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    /** @var \Doctrine\ORM\QueryBuilder $qb */
                    $qb = $filterQuery->getQueryBuilder();
                    $qb->leftJoin($filterQuery->getRootAlias() . ".ingredients", 'ci')
                        ->leftJoin("ci.ingredient", 'ing')
                        ->andWhere('ing.isInStock = 1');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Cocktail::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}