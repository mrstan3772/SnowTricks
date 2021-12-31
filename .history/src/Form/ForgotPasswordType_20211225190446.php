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
                    'invalid_message' =>  'Les adresses e-mail doivent être identiques',
                    'required' => true,
                    // 'constraints' => [
                    //     new NotBlank(),
                    //     new Email()
                    // ],
                    'first_option' => [
                        'label' => 'Saisir votre adresse e-mail'
                    ],
                    'second_option' => [
                        'label' => 'Confirmez votre adresse e-mail'
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