<?php

namespace App\Form;

use App\Entity\Advantages;
use App\Entity\Categories;
use App\Entity\Jobs;
use App\Repository\AdvantagesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
                'label' => 'Type de contrat', 
                'placeholder' => 'Choisissez un type de contrat',
                'choices'  => [
                    "SUR PLACE" => "SUR PLACE",
                    "HYBRIDE" => "HYBRIDE",
                    "A DISTANCE" => "A DISTANCE",
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
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Date de début de mission',
                'attr' => array(
                    'placeholder' => 'Date du début de la mission'
                )
            ])
            ->add('schedule', ChoiceType::class, [
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
                    'class' => 'd-flex flex-column col-md-6 mb-3'
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
                'label' => 'Décrivez la mission en ligne',
                'attr' => array(
                    'placeholder' => 'Décrivez les missions principales en une ligne'
                )
            ])
            ->add('mainMissions', TextareaType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Mission(s) principales',
                'attr' => array(
                    'placeholder' => 'Séparez chaque mission par un tiret (-)'
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
            ->add('profileRequirements', TextareaType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Compétences recherchées',
                'attr' => array(
                    'placeholder' => 'Séparez chaque compétence par un tiret (-)'
                )
            ])
            ->add('informations', TextareaType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Informations clés du poste',
                'attr' => array(
                    'placeholder' => 'Séparez chaque information par un tiret (-)'
                )
            ])
            ->add('category', EntityType::class, array(
                'label' => "Choisissez une catégorie",
                'placeholder' => "Choisissez une catégorie",
                'class' => Categories::class,
                'query_builder' => function (CategoriesRepository $er) {
                     return $er->createQueryBuilder('c');
                },
            ))
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
            ->add('course', EntityType::class, array(
                'label' => "Choisissez une thématique",
                'placeholder' => "Choisissez une thématique",
                'class' => Categories::class,
                'query_builder' => function (CategoriesRepository $er) {
                     return $er->createQueryBuilder('c');
                },
            ))
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
