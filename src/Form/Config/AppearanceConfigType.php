<?php

namespace App\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

class AppearanceConfigType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo', DropzoneType::class, [
                'required'    => false,
                'mapped'      => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '2048k',
                        'mimeTypes'        => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (jpg, png, webp, svg)',
                    ]),
                ],
            ])
            ->add('theme', ChoiceType::class, [
                'label'    => 'Main Theme',
                'required' => true,
                'choices'  => ['Dark theme' => 'dark', 'Light theme' => 'light'],
            ])
            ->add('themePrimaryColor', TextType::class, [
                'label'    => 'Primary Accent Color (hexadecimal)',
                'help'     => 'Default value : #A88C6A',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
