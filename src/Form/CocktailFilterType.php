<?php

namespace App\Form;

use App\Entity\Cocktail;
use App\Entity\Glass;
use App\Entity\Ingredient;
use App\Form\Type\AutocompleteEntityType;
use Doctrine\ORM\Query\Expr\Join;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CocktailFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('cocktail', AutocompleteEntityType::class, [
                'class' => Cocktail::class,
                'routeName' => 'app_cocktail_autocomplete',
                'autosubmit' => true,
                'mapped' => false,
                'required' => false,
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    /** @var \Doctrine\ORM\QueryBuilder $qb */
                    $qb = $filterQuery->getQueryBuilder();
                    if ($values['value'] instanceof Cocktail)
                        $qb->andWhere($filterQuery->getRootAlias() . '.id = ' . $values['value']->getId());
                },
            ])
            ->add('ingredients', AutocompleteEntityType::class, [
                'class' => Ingredient::class,
                'routeName' => 'app_ingredient_autocomplete',
                'autosubmit' => true,
                'mapped' => false,
                'required' => false,
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    /** @var \Doctrine\ORM\QueryBuilder $qb */
                    $qb = $filterQuery->getQueryBuilder();
                    if ($values['value'] instanceof Ingredient)
                        $qb->andWhere('ci.ingredient = ' . $values['value']->getId());
                },
            ])
            ->add('glass', Filters\EntityFilterType::class, [
                'class' => Glass::class,
                'required' => false,
                'attr' => ['data-autosubmit' => 'true']
            ])
            ->add('fake', HiddenType::class, [
                'mapped' => false,
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    /** @var \Doctrine\ORM\QueryBuilder $qb */
                    $qb = $filterQuery->getQueryBuilder();
                    $qb->leftJoin($filterQuery->getRootAlias() . '.ingredients', 'ci')
                        ->leftJoin('ci.ingredient', 'i1')
                        ->leftJoin('ci.ingredient', 'i2', Join::WITH, $qb->expr()->andX(
                            $qb->expr()->eq('i2.id', 'ci.ingredient'),
                            $qb->expr()->eq('i2.isInStock', '1')
                        ))
                        ->addOrderBy($filterQuery->getRootAlias() . '.isFeatured', 'DESC')
                        ->addOrderBy($filterQuery->getRootAlias() . '.name')
                        ->groupBy($filterQuery->getRootAlias() . '.id')
                        ->having('COUNT(i1.id) = COUNT(i2.id)');
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
