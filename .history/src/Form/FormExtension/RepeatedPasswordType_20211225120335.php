<?php

namespace App\Form\FormExtension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RepeatedPasswordType extends AbstractType
{
    public function getParent(): string
    {
        return RepeatedType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe saisis ne correspondent pas',
                'required' => true,
                'first_name' => 'pass',
                'second_name' => 'confirm',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿÀ-ZÀ-Ý0-9]).{12,}$',
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
                    'pattern' => "^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿÀ-ZÀ-Ý0-9]).{12,}$\gm",
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
}
