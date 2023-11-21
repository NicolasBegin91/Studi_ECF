<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Validator\Constraints as Assert;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', Type\TextType::class, [
                'label' => 'Avis_Form_Username',
                'mapped' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un nom',
                    ]),
                    new Assert\Length([
                        'min' => 4,
                        'minMessage' => 'Veuillez entrer un nom d\'au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('content', Type\TextareaType::class, [
                'label' => 'Avis_Form_Content',
                'mapped' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un commentaire',
                    ]),
                    new Assert\Length([
                        'min' => 15,
                        'minMessage' => 'Veuillez entrer un commentaire d\'au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add(
                'note',
                Type\ChoiceType::class,
                [
                    'label' => 'Avis_Form_Note',
                    'choices'  => [
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                    ],
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
