<?php

namespace App\Form;

use App\Entity\Advantages;
use App\Entity\Jobs;
use App\Repository\AdvantagesRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Titre de la mission',
                'attr' => array(
                    'placeholder' => 'Titre de la mission'
                )
            ])
            ->add('city', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Titre de la mission',
                'attr' => array(
                    'placeholder' => 'Titre de la mission'
                )
            ])
            ->add('salary')
            ->add('contract')
            ->add('description')
            ->add('titleDescription', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Titre de la mission',
                'attr' => array(
                    'placeholder' => 'Titre de la mission'
                )
            ])
            ->add('location', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Titre de la mission',
                'attr' => array(
                    'placeholder' => 'Titre de la mission'
                )
            ])
            ->add('date', DateType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Titre de la mission',
                'attr' => array(
                    'placeholder' => 'Titre de la mission'
                )
            ])
            ->add('schedule',ChoiceType::class, [
                'label' => 'Horaires', 
                'placeholder' => 'Choisissez les horaires',
                'choices'  => [
                    "08h-12h00" => "J'ai besoin de formateurs",
                    "09h-12h30" => "J'ai besoin de formateurs",
                    "08h-17h00" => "J'ai besoin de formateurs",
                    "08h30-17h30" => "J'ai besoin de formateurs",
                    "09h-17h00" => "J'ai besoin de formateurs",
                    "09h30-17h30" => "J'ai besoin de formateurs",
                    "13h30-17h30" => "J'ai besoin de formateurs",
                    'Autres' => 'Autres',
                ],
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'expanded'=>false,
                'multiple'=>false,
                'attr' => array(
                    'placeholder' => 'Pays'
                )
                
            ])
            ->add('missionDescription', TextareaType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Titre de la mission',
                'attr' => array(
                    'placeholder' => 'Titre de la mission'
                )
            ])
            ->add('mainMissions')
            ->add('profileDescription')
            ->add('profileRequirements')
            ->add('informations')
            ->add('category')
            ->add('advantages', EntityType::class, [
                'class' => Advantages::class,
                'query_builder' => function (AdvantagesRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.id', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
                
            ])
            ->add('course')
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary mt-4 buttonBorderRadius text-center',
                ),
                'label' => 'Modifier le mot de passe',
                'row_attr' => [
                    'class' => 'text-center mb-0'
                ]
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jobs::class,
        ]);
    }
}
