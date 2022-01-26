<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'comment_content',
                TextareaType::class,
                [
                    'label' => 'Laisser un message',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 30,
                                'max' => 5000,
                                'minMessage' => 'Votre message doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'Votre message ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'attr' => [
                        'placeholder' => "Écrivez votre message ici...",
                        'title' => 'Laisser un message',
                        'minlenght' => 30,
                        'maxlength' => 5000
                    ],
                    'help' => 'Ce message doit contenir entre 30 et 5000 caractères.',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
