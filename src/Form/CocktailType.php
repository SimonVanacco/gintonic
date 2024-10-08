<?php

namespace App\Form;

use App\Entity\Cocktail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

class CocktailType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', null, ['attr' => ['class' => 'tinymce']])
            ->add('recipe', null, ['attr' => ['class' => 'tinymce']])
            ->add('photo', DropzoneType::class, [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cocktail::class,
        ]);
    }
}
