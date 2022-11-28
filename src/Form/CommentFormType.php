<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'attr' => [
                    "class" => "form-control"
                ]
            ])
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Entrez votre e-mail',
                    'attr' => [
                        "class" => "form-control"
                    ]
                ]
            )
            ->add(
                'content',
                CKEditorType::class,
                [
                    'label' => 'Votre message',
                    'config' => [
                        'uiColor' => '#ffffff'
                    ],
                    'attr' => [
                        "class" => "form-control"
                    ]
                ]
            )
            ->add(
                'Envoyer',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn formBtn mt-3'
                    ]
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
