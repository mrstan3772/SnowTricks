<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => ['autofocus' => true, 'placeholder' => 'John'],
                    'help' => 'Identifiant d\'authentification, il doit être unique',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => ['placeholder' => 'johndoe@snowtricks.com'],
                    'help' => 'Une adresse email relié à aucun compte',
                ]
            )
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue(
                            [
                                'message' => 'Vous devez accepter nos conditions',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe saisis ne correspondent pas'
                    'help' => 'Le mot de passe doit comporter au minimum 6 caractères.',
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Veuillez entrer un mot de passe',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 6,
                                'minMessage' => 'Votre mot de passe doit respecté une taille de {{ limit }} caractères',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]
                        ),
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'constraints' => [
                    new UniqueEntity(
                        [
                            'fields' => ['username', 'email']
                        ]
                    ),
                ],
            ]
        );
    }
}
