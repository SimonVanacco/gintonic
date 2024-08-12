<?php

namespace App\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainConfigType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adminEmail', EmailType::class, [
                'label'    => 'Administrator email address',
                'help'     => 'Address to which notification emails will be sent',
                'required' => true,
            ])
            ->add('fromEmail', EmailType::class, [
                'label'    => 'Email notification sender',
                'help'     => 'Address used to send emails',
                'required' => true,
            ])
            ->add('ordersOpen', ChoiceType::class, [
                'label'    => 'Are orders open ?',
                'required' => true,
                'choices'  => ['Yes' => '1', 'No' => '0'],
            ])
            ->add('smsNotificationTo', TelType::class, [
                'label'    => 'Notify this phone number of order by SMS',
                'help'     => 'Leave empty if not desired',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
