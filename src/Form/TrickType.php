<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trick = $options['data'] ?? null;
        $isEdit = $trick && $trick->getId();

        $defaultId = $isEdit ? $trick->getTrickGroupId() : 41;

        $builder
            ->add(
                'trick_name',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Saisir le nom de la figure',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'Le nom de la figure doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'Le nom de la figure ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'attr' => [
                        'autofocus' => true,
                        'placeholder' => 'Japan Air',
                        'title' => 'Saisir le nom de la figure',
                        'minlenght' => 3,
                        'maxlength' => 255
                    ],
                    'help' => 'Le titre de la figure, il doit être unique',
                ]
            )
            ->add(
                'trick_description',
                TextareaType::class,
                [
                    'required' => true,
                    'label' => 'Saisir une description',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 25,
                                'max' => 10000,
                                'minMessage' => 'Votre description doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'Votre description ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'attr' => [
                        'placeholder' => 'Japan Air - A very tweaked mute air where the skater pulls the board up behind his back and knees pointed down.',
                        'title' => 'Saisir la description de la figure',
                        'minlenght' => 25,
                        'maxlength' => 10000,
                        'rows' => 25
                    ],
                    'help' => 'Renseigné une description aussi fourni que possible. Utilisez du Markdown pour formater le contenu de l\'article. Le HTML est également autorisé.',
                ]
            )
            ->add(
                'trick_group_id',
                ChoiceType::class,
                [
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Définir un groupe',
                    'constraints' => [
                        new NotBlank(),
                        new Choice([41, 42, 43, 44, 45])
                    ],
                    'choices'  => [
                        'Groupe 1' => 41,
                        'Groupe 2' => 42,
                        'Groupe 3' => 43,
                        'Groupe 4' => 44,
                        'Groupe 5' => 45,
                    ],
                    'data' => $defaultId,
                    'attr' => [
                        'title' => 'Sélectionnez un groupe',
                    ],
                    'help' => 'Sélectionner un groupe auquel associer cette figure',
                ]
            )
            ->add(
                'trick_creation_date',
                DateTimePickerType::class,
                [
                    'required' => true,
                    'label' => 'Saisir une date',
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'attr' => [
                        'title' => 'Saisir une date',
                    ],
                    'help' => 'Saisir une date pour  la publication de la figure.',
                ]
            );
        // ->add('trick_update_date')
        // ->add('trick_thumbnail')
        // ->add('trick_slug')
        // ->add('trick_author')
        // ->add('trick_group');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
