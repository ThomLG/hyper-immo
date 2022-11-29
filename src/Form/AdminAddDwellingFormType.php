<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Dwelling;
use App\Entity\Type;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAddDwellingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('adress', TextType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('zipcode', TextType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('city', TextType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('size', TextType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('price', TextType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('description', CKEditorType::class, [
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add(
                'type',
                EntityType::class,
                [
                    'class' => Type::class,
                    'choice_label' => 'name',
                    'attr' => [
                        "class" => "form-select"
                    ]
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'attr' => [
                        "class" => "form-select"
                    ]
                ]
            )
            ->add('picture', FileType::class, [
                'data_class' => null,
                'attr' =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add(
                'Valider',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn formBtn'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dwelling::class,
        ]);
    }
}
