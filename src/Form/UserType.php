<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'User_Form_Email',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un email',
                    ]),
                    new Assert\Email([
                        'message' => 'Veuillez entrer un email valide',
                    ]),
                ],
            ])
            ->add('username', null, [
                'label' =>
                'User_Form_Username',
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
            ->add('roles', Type\ChoiceType::class, [
                'label' => 'User_Form_Role',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('password', null, ['label' => 'User_Form_Password'])
            ->add('birthDate', Type\DateType::class, [
                'label' => 'User_Form_BirthDate',
                'widget' => 'choice',
                'input'  => 'datetime',
                'days' => range(1, 31),
                'months' => range(1, 12),
                'years' => range(1900, 2013),
                'format' => 'ddMMyyyy',
            ])
            ->add('pictureName', null, ['label' => 'User_Form_ImageName'])
            ->add('image', Type\FileType::class, [
                'label' => 'User_Form_Image_Button',
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
            ]);

        if ($options['passwordRequired']) {
            $builder
                ->add('plainPassword', Type\PasswordType::class, [
                    'label' => 'User_Form_Password',
                    'mapped' => false,
                    'required' => $options['passwordRequired'],
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Veuillez entrer un mot de passe d\'au moins {{ limit }} caractères',
                            'max' => 255,
                        ]),
                    ],
                ]);
        }

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordRequired' => true
        ]);

        $resolver->setAllowedTypes('passwordRequired', 'bool');
    }
}
