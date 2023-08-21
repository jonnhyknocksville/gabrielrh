<?php

namespace App\Form;

use App\Entity\JobApplication;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
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
            ->add('firstName', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Prénom',
                'attr' => array(
                    'placeholder' => 'Prénom'
                ),
            ])
            ->add('lastName', TextType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Nom',
                'attr' => array(
                    'placeholder' => 'Nom'
                ),
            ])
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Email',
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
                'label' => 'Téléphone',
                'attr' => array(
                    'placeholder' => 'Téléphone'
                )
            ])
            ->add('mobility', TelType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Téléphone',
                'attr' => array(
                    'placeholder' => 'Téléphone'
                )
            ])
            ->add('diploma', TelType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'label' => 'Téléphone',
                'attr' => array(
                    'placeholder' => 'Téléphone'
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
                'label' => 'Message',
                'attr' => array(
                    'placeholder' => 'Votre message'
                )
            ])
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
            'data_class' => JobApplication::class,
        ]);
    }
}
