<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option) : void 
    {
        $builder
        ->add(
            'email', 
            RepeatedType::class, 
            [
            'type' => EmailType::class,
            'invalid_message' =>  'Les adresses e-mail doivent être identiques',
            ]
            );
    }

}