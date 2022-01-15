<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
