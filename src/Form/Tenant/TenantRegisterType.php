<?php

namespace App\Form\Tenant;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TenantRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email*',
                'label_attr' => ['class' => 'col-form-label'],
                'attr' => [
                    'placeholder' => 'johndoe@example.com',
                    'class' => 'form-control',
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe',
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Nom*',
                'label_attr' => ['class' => 'col-form-label'],
                'attr' => [
                    'placeholder' => 'John',
                    'class' => 'form-control',
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prénom*',
                'label_attr' => ['class' => 'col-form-label'],
                'attr' => [
                    'placeholder' => 'Doe',
                    'class' => 'form-control',
                ]
            ])
            ->add('birthday', null, [
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'input' => 'datetime_immutable',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone*',
                'label_attr' => ['class' => 'col-form-label'],
                'attr' => [
                    'placeholder' => '0612345678',
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
