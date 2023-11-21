<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Validator\Constraints as Assert;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', null, [
                'label' => 'Vehicule_Form_Model',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un modèle',
                    ])
                ],
            ])
            ->add('registrationNumber', null, [
                'label' => 'Vehicule_Form_Registration',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une immatriculation',
                    ]),
                ],
            ])
            ->add('year', Type\IntegerType::class, [
                'label' => 'Vehicule_Form_Year',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une année',
                    ]),
                    new Assert\PositiveOrZero([
                        'message' => "L'année doit être positive",
                    ]),
                ],
            ])
            ->add('price', Type\IntegerType::class, [
                'label' => 'Vehicule_Form_Price',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une année',
                    ]),
                    new Assert\PositiveOrZero([
                        'message' => "L'année doit être positive",
                    ]),
                ],
            ])
            ->add('mileage', Type\IntegerType::class, [
                'label' => 'Vehicule_Form_Mileage',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une distance',
                    ]),
                    new Assert\PositiveOrZero([
                        'message' => 'La distance doit être positive',
                    ]),
                ],
            ])
            ->add('pictureName', null, ['label' => 'Vehicule_Form_ImageName'])
            ->add('image', Type\FileType::class, [
                'label' => 'Vehicule_Form_Image_Button',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'extensions' => [
                            'png',
                            'jpeg',
                            'jpg',
                        ],
                        'extensionsMessage' => "L'extension du fichier est incorrecte : ({{ extension }}). Les extentions autorisées sont : {{ extensions }}",
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Le fichier est trop lourd ({{ size }} {{ suffix }}). Le poids maximum est de {{ limit }} {{ suffix }}.'
                    ])
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
