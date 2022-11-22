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
                'attr' => [
                    "class" => "form-control"
                ]
            ])
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        "class" => "form-control"
                    ]
                ]
            )
            ->add(
                'content',
                CKEditorType::class,
                [
                    'config'=>[
                    'uiColor'=>'#ffffff'
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
                        'class' => 'btn btn-primary'
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