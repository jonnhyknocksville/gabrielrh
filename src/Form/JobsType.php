<?php

namespace App\Form;

use App\Entity\Advantages;
use App\Entity\Courses;
use App\Entity\Jobs;
use App\Entity\Themes;
use App\Repository\AdvantagesRepository;
use App\Repository\CoursesRepository;
use App\Repository\ThemesRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'label' => 'Ville où est la mission',
                'attr' => array(
                    'placeholder' => 'Ville où est la mission'
                )
            ])
            ->add('salary', ChoiceType::class, [
                'label' => 'Taux horaire', 
                'placeholder' => 'Choisissez un taux horaire',
                'choices'  => [
                    "20€/H" => "20€/H",
                    "25€/H" => "25€/H",
                    "30€/H" => "30€/H",
                    "35€/H" => "35€/H",
                    "40€/H" => "40€/H",
                    "45€/H" => "45€/H",
                    "50€/H" => "50€/H",
                    "55€/H" => "55€/H",
                    "60€/H" => "60€/H",
                    "65€/H" => "65€/H",
                    'Autres' => 'Autres',
                ],
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'expanded'=>false,
                'multiple'=>false,
                'attr' => array(
                    'placeholder' => 'Pays'
                )
                
            ])
            ->add('contract', ChoiceType::class, [
                'label' => 'Type de contrat', 
                'placeholder' => 'Choisissez un type de contrat',
                'choices'  => [
                    "CDI" => "CDI",
                    "CDD" => "CDD",
                    "FREELANCE" => "FREELANCE",
                    'Autres' => 'Autres',
                ],
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'expanded'=>false,
                'multiple'=>false,
                'attr' => array(
                    'placeholder' => 'Pays'
                )
                
            ])
            ->add('location', ChoiceType::class, [
                'label' => 'Lieu de réalisation', 
                'placeholder' => 'Comment se fera la prestation?',
                'choices'  => [
                    "SUR PLACE" => "SUR PLACE",
                    "HYBRIDE" => "HYBRIDE",
                    "A DISTANCE" => "A DISTANCE",
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
            ->add('titleDescription', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Titre de la description',
                'attr' => array(
                    'placeholder' => 'Précisez un titre pour la description du poste'
                )
            ])
            ->add('description', TextareaType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Description du poste',
                'attr' => array(
                    'placeholder' => 'Décrivez le poste en 5-6 paragraphes maximum'
                ),
            ])
            ->add('date', DateType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Date de début de mission',
                'attr' => array(
                    'placeholder' => 'Date du début de la mission'
                )
            ])

            ->add('updatedAt', DateType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Mis à jour le',
                'attr' => array(
                    'placeholder' => 'Mis à jour le'
                )
            ])

            ->add('available', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
            ])
            ->add('schedule', ChoiceType::class, [
                'label' => 'Horaires', 
                'placeholder' => 'Choisissez les horaires',
                'choices'  => [
                    "08h-12h00" => "08h-12h00",
                    "09h-12h30" => "09h-12h30",
                    "08h-17h00" => "08h-17h00",
                    "08h30-17h30" => "08h30-17h30",
                    "09h-17h00" => "09h-17h00",
                    "09h30-17h30" => "09h30-17h30",
                    "13h30-17h30" => "13h30-17h30",
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
            ->add('missionDescription', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Décrivez la mission en une ligne',
                'attr' => array(
                    'placeholder' => 'Décrivez les missions principales en une ligne'
                )
            ])
            ->add('mainMissions', TextType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Mission(s) principales',
                'attr' => array(
                    'placeholder' => 'Ajouter une mission'
                )
            ])
            ->add('profileDescription', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Profil recherché',
                'attr' => array(
                    'placeholder' => 'Décrivez en une phrase simple le profil recherché'
                )
            ])
            ->add('profileRequirements', TextType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Compétences recherchées',
                'attr' => array(
                    'placeholder' => 'Ajouter une compétence recherchée'
                )
            ])
            ->add('informations', TextType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Informations clés du poste',
                'attr' => array(
                    'placeholder' => 'Ajouter une information'
                )
            ])
            ->add('category', EntityType::class, array(
                'label' => "Choisissez un thème lié au poste",
                'placeholder' => "Choisissez un thème",
                'class' => Themes::class,
                'query_builder' => function (ThemesRepository $er) {
                     return $er->createQueryBuilder('t');
                },
            ))
            ->add('course', EntityType::class, array(
                'label' => "Choisissez une thématique",
                'placeholder' => "Choisissez une thématique",
                'class' => Courses::class,
                'query_builder' => function (CoursesRepository $er) {
                     return $er->createQueryBuilder('c');
                },
            ))
            ->add('advantages', EntityType::class, [
                'label' => "Quels sont les avantages liés au poste?",
                'class' => Advantages::class,
                'query_builder' => function (AdvantagesRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.id', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
                
            ])
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-lg btn-outline-secondary mt-4 buttonBorderRadius text-center',
                ),
                'label' => 'Créer la mission',
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
