<?php

namespace App\Form;

use App\Entity\JobApplication;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class JobApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job', HiddenType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'mapped' => false
            ])
            ->add('firstName', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Votre Prénom',
                'attr' => array(
                    'placeholder' => 'Prénom'
                ),
            ])
            ->add('lastName', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Votre Nom',
                'attr' => array(
                    'placeholder' => 'Nom'
                ),
            ])
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Votre email',
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ])
            ->add('phone', TelType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Téléphone',
                'attr' => array(
                    'placeholder' => 'Téléphone'
                )
            ])
            ->add('yearsExperience', TelType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Années d\'expérience',
                'attr' => array(
                    'placeholder' => 'Année d\'expérience'
                )
            ])
            ->add('mobility', ChoiceType::class, [
                'label' => 'Votre mobilité', 
                'placeholder' => 'Où pouvez-vous réaliser cette mission?',
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
            ])
            ->add('diploma', TelType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Votre dernier diplôme lié à ce poste',
                'attr' => array(
                    'placeholder' => 'Votre dernier diplôme'
                )
            ])
            ->add('cvFile', VichFileType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'CV'
            ])
            ->add('motivation', TextareaType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'label' => 'Quelques mots de motivation',
                'attr' => array(
                    'placeholder' => 'Dites-nous quelques mots sur votre motivation'
                )
            ])
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary mt-4 text-light smallBorderRadius text-center',
                ),
                'label' => 'POSTULER',
                'row_attr' => [
                    'class' => 'text-center mb-0'
                ]
            ))
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale' => 'fr',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobApplication::class,
        ]);
    }
}
