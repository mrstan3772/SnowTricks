<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
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
                    'invalid_message' =>  'Les noms d\'utilisateurs e-mail doivent être identiques',
                    'required' => true,
                    // 'constraints' => [
                    //     new NotBlank(),
                    // ],
                    'first_option' => [
                        'label' => 'Saisir votre nom d\'utilisateur'
                    ],
                    'second_option' => [
                        'label' => 'Confirmez votre nom d\'utilisateur'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
