<?php

namespace App\Form;

use App\Entity\User;
use App\Form\FormExtension\RepeatedPasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
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
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'Le nom d\'utilisateur doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'Le nom d\'utilisateur ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'attr' => ['autofocus' => true, 'placeholder' => 'John'],
                    'help' => 'Identifiant d\'authentification, il doit être unique',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Email()
                    ],
                    'attr' => ['placeholder' => 'johndoe@snowtricks.com'],
                    'help' => 'Une adresse email relié à aucun compte'
                ]
            )
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'mapped' => false,
                    'required' => true,
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
                RepeatedPasswordType::class
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
                            'fields' => ['username', 'email'], 
                            'message' => ''
                        ]
                    ),
                ],
                'validation_groups' => ['registration'],
            ]
        );
    }
}
