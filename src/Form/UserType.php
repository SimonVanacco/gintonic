<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'required' => true,
            ])
            ->add('lastName', null, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options'         => ['attr' => ['class' => 'password-field']],
                'required'        => false,
                'mapped'          => false,
                'first_options'   => ['label' => 'Password'],
                'second_options'  => ['label' => 'Repeat Password'],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user || null === $user->getId()) {
                // Password field is mandatory when creating a new user
                $form->remove('password');
                $form->add('password', RepeatedType::class, [
                    'type'            => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options'         => ['attr' => ['class' => 'password-field']],
                    'required'        => true,
                    'mapped'          => false,
                    'first_options'   => ['label' => 'Password'],
                    'second_options'  => ['label' => 'Repeat Password'],
                    'constraints'     => [
                        new Assert\NotBlank(),
                    ],
                ]);
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
