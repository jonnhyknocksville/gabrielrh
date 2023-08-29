<?php

namespace App\Form;

use App\Entity\ProfessionalsNeeds;
use App\Repository\ThemesRepository;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Proxies\__CG__\App\Entity\Themes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class FindTeachersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Prénom'
                )
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Nom'
                )
            ])
            ->add('company', TextType::class, [
                'label' => 'Ecole/Entreprise',
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Ecole/Entreprise'
                )
            ])
            ->add('current_job', ChoiceType::class, [
                'label' => 'Choisissez un poste', 
                'placeholder' => 'Quel est votre poste actuel?',
                'choices'  => [
                    'Responsable pédagogique' => 'Responsable pédagogique',
                    'Directeur/Directrice' => 'Directeur/Directrice',
                    'Formateurs' => 'Formateurs',
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
            ->add('email', TextType::class, [
                'label' => 'Email',
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Téléphone'
                )
            ])
            ->add('profil', ChoiceType::class, [
                'label' => 'Quel profil?', 
                'placeholder' => 'Choisissez un profil',
                'choices'  => [
                    'Formateur' => 'Formateur',
                    'Consultant' => 'Consultant',
                    'Autres' => 'Autres',
                ],
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'expanded'=>false,
                'multiple'=>false,
            ])
            ->add('date', DateType::class, [
                'label' => 'Quand?',
                'required' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Pour quand?'
                ),
                'placeholder' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour' )
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Où?',
                'required' => false,
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Où?'
                )
            ])
            ->add('theme', EntityType::class, array(
                'label' => "Choisissez une thématique",
                'placeholder' => "Choisissez une thématique",
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'class' => Themes::class,
                'query_builder' => function (ThemesRepository $er) {
                     return $er->createQueryBuilder('c');
                },
                'choice_label' => 'title',
                'choice_value' => 'id',
            ))
            ->add('motive', ChoiceType::class, [
                'label' => 'Choisissez un motif', 
                'placeholder' => '--',
                'choices'  => [
                    "J'ai besoin de formateurs" => "J'ai besoin de formateurs",
                    "J'ai besoin de contenus e-learning" => "J'ai besoin de contenus e-learning",
                    "Je souhaite simplement échanger" => "Je souhaite simplement échanger",
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
            ->add('message', TextareaType::class, [
                'label' => 'Ecrivez vos besoins',
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Vos besoins'
                )
            ])
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary mt-4 text-light smallBorderRadius text-center',
                ),
                'label' => 'Demander un formateur',
                'row_attr' => [
                    'class' => 'text-center mb-0'
                ]
            ))
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale' => 'de',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfessionalsNeeds::class,
        ]);
    }
}
