<?php

namespace App\Form\Garage;

use App\Entity\Garage;
use App\Form\CityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class GarageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'form-control',
                    'rows' => 30,
                    'cols' => 50
                ]
            ])
            ->add('pricePerHour', MoneyType::class, [
                'label' => 'Prix à l\'heure',
                'currency' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix par heure'
                ]
            ])
            ->add('pricePerDay', MoneyType::class, [
                'label' => 'Prix à la journée',
                'currency' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix par jour',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse postale complete',
                    'class' => 'form-control',
                ]
            ])
            ->add('city', CityType::class)
            ->add('images', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Les formats accepté son les suivants : gif, jpeg, jpg, png',
                        ]),
                    ]),
                ],
            ])
            ->add('defaultImage', HiddenType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Garage::class,
        ]);
    }
}
