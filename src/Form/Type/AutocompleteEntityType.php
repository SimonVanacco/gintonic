<?php

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Autocomple.js Ajax Form Type
 * @see /assets/controllers/autocomplete_controller.js
 * @see /templates/
 */
class AutocompleteEntityType extends AbstractType {

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'autosubmit' => false,
            'routeName' => "",
            'routeParams' => [],
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void {
        $view->vars['uri'] = $this->urlGenerator->generate($options['routeName'], $options['routeParams']);
        $view->vars['autosubmit'] = $options['autosubmit'];
    }

    public function getParent(): string {
        return EntityType::class;
    }

}