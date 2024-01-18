<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Country;
use App\Entity\Stamp;
use App\Helper\Enum\Type\Car as EnumCarType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
                'required' => true,
                'attr' => ['maxlength' => 255],
            ])
            ->add('isPopular', CheckboxType::class, [
                'label' => 'Является популярной',
                'required' => false,
            ])
            ->add('typeEngine', ChoiceType::class, [
                'label' => 'Тип двигателя',
                'choices' => EnumCarType::getNamesForFormType(),
                'required' => true,
            ])
            ->add('weight', TextType::class, [
                'label' => 'Вес',
                'attr' => ['maxlength' => 255, 'placeholder' => '2 000 кг'],
            ])
            ->add('size', TextType::class, [
                'label' => 'Габариты',
                'attr' => ['maxlength' => 255, 'placeholder' => '4390 × 1790 × 1560 мм'],
            ])
            ->add('power', TextType::class, [
                'label' => 'Мощность',
                'attr' => ['maxlength' => 255, 'placeholder' => '134 — 150 кВт'],
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Год выпуска',
                'attr' => [
                    'min' => 1900,
                    'max' => 3000,
                    'value' => (new \DateTime())->format('Y')
                ],
            ])
            ->add('battery', TextType::class, [
                'label' => 'Емкость батареи',
                'attr' => ['maxlength' => 255, 'placeholder' => '53.6 — 68.8 кВт.ч'],
            ])
            ->add('mileage', IntegerType::class, [
                'label' => 'Пробег',
                'attr' => ['min' => 0, 'placeholder' => '69 000'],
            ])
            ->add('fullPrice', IntegerType::class, [
                'label' => 'Цена полной комплектации',
                'required' => true,
                'attr' => ['min' => 0, 'placeholder' => '3300000'],
            ])
            ->add('standardPrice', IntegerType::class, [
                'label' => 'Цена стандартной комплектации',
                'attr' => ['min' => 0, 'placeholder' => '25490000'],
                'required' => false,
            ])
            ->add('stamp', TextType::class, [
                'label' => 'Марка',
                'attr' => ['maxlength' => 255, 'placeholder' => 'Хундей'],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Страна',
            ])
            ->add('stamp', EntityType::class, [
                'class' => Stamp::class,
                'choice_label' => 'name',
                'label' => 'Марка',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'mapped' => false,
                'label' => 'Файлы изображения',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'attr' => ['class' => 'images'],
                    'label' => false,
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '10M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                                'image/svg+xml',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Загрузите валидное изображение',
                        ])
                    ]
                ],
                'prototype_name' => '__images__',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
