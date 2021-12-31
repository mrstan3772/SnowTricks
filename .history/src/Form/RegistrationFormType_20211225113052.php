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
                    'required' => true,
                    'attr' => ['autofocus' => true, 'placeholder' => 'John'],
                    'help' => 'Identifiant d\'authentification, il doit être unique',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
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
                    'invalid_message' => 'Les mots de passe saisis ne correspondent pas',
                    'required' => true,
                    'first_name' => 'pass',
                    'second_name' => 'confirm',
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'label_attr' => [
                            'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères, dont 1 lettre en minuscule, 1 lettre en majuscule, 1 chiffre et un caractère spécial (ordre aléatoire).'
                        ],
                        'attr' => [
                            'pattern' => "^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿÀ-ZÀ-Ý0-9]).{12,}$",
                            'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères, dont 1 lettre en minuscule, 1 lettre en majuscule, 1 chiffre et un caractère spécial (ordre aléatoire).',
                            'minlenght' => 6,
                            'maxlength' => 255
                        ],
                        'help' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères, dont 1 lettre en minuscule, 1 lettre en majuscule, 1 chiffre et un caractère spécial (ordre aléatoire).',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                        'label_attr' => [
                            'title' => 'Confirmer votre mot de passe'
                        ],
                        'attr' => [
                            'pattern' => "^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿÀ-ZÀ-Ý0-9]).{12,}$",
                            'title' => 'Confirmer votre mot de passe',
                            'minlenght' => 6,
                            'maxlength' => 255
                        ],
                    ],
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Veuillez entrer un mot de passe',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 6,
                                'minMessage' => 'Votre mot de passe doit respecté une taille de {{ limit }} caractères au minimum',
                                'max' => 255,
                                'maxMessage' => 'Votre mot de passe doit respecté une taille de {{ limit }} caractères au maximum',
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
