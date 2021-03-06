<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option): void
    {
        $builder
            ->add(
                'username',
                RepeatedType::class,
                [
                    'type' => TextType::class,
                    'invalid_message' =>  'Les noms d\'utilisateur doivent être identiques',
                    'required' => true,
                    'first_name' => 'username',
                    'second_name' => 'confirm',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'Le mot de passe pour un compte utilisateur doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'Le mot de passe pour un compte utilisateur ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'first_options' => [
                        'label' => 'Saisir votre nom d\'utilisateur',
                        'attr' => [
                            'placeholder' => "johndoe",
                            'title' => 'Saisir un nom d\'utilisateur',
                            'minlenght' => 3,
                            'maxlength' => 255
                        ],
                        'help' => 'Celui-ci doit être connu de nos services.'
                    ],
                    'second_options' => [
                        'label' => 'Confirmez votre nom d\'utilisateur',
                        'attr' => [
                            'placeholder' => "johndoe",
                            'title' => 'Confirmer votre nom d\'utilisateur',
                            'minlenght' => 3,
                            'maxlength' => 255
                        ],
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
