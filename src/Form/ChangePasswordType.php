<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array(
                'mapped' => false,
                'label' => 'Ancien mot de passe',
                'row_attr' => [
                    'class' => 'col-md-12 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Ancien mot de passe'
                )
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  =>
                ['label' => 'Nouveau mot de passe',
                'row_attr' => [
                    'class' => 'col-md-12 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Nouveau mot de passe'
                )
                ],
                'second_options' =>
                ['label' => 'Répétez le mot nouveau mot de passe',
                'row_attr' => [
                    'class' => 'col-md-12 mb-3'
                    ],
                'attr' => array(
                    'placeholder' => 'Nouveau mot de passe'
                )
                ],
                'invalid_message' => 'Veuillez saisir deux nouveaux mots de passe identiques'

            ))
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary mt-4 text-light smallBorderRadius text-center',
                ),
                'label' => 'Modifier le mot de passe',
                'row_attr' => [
                    'class' => 'text-center mb-0'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
