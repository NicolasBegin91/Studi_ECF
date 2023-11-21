<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Service_Form_Name',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un nom',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Veuillez entrer un nom d\'au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('description', Type\TextareaType::class, [
                'label' =>
                'Service_Form_Desc',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une description',
                    ]),
                    new Assert\Length([
                        'min' => 15,
                        'minMessage' => 'Veuillez entrer une description d\'au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('price', null, [
                'label' => 'Service_Form_Price',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un prix',
                    ]),
                    new Assert\PositiveOrZero([
                        'message' => 'Le prix doit être positif'
                    ]),
                ],
            ])
            ->add('time', null, [
                'label' =>
                'Service_Form_Time',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une durée',
                    ]),
                    new Assert\PositiveOrZero([
                        'message' => 'La durée doit être positive'
                    ]),
                ],
            ])
            ->add('pictureName', null, ['label' => 'Service_Form_ImageName'])
            ->add('image', Type\FileType::class, [
                'label' => 'Service_Form_Image_Button',
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
            'data_class' => Service::class,
        ]);
    }
}
