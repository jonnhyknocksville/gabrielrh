<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'attr' => array(
                    'placeholder' => 'Prénom'
                )                
                
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => array(
                    'placeholder' => 'Nom'
                )
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ])
            ->add('phone', TelType::class, [                
                'label' => 'Téléphone',
                'attr' => array(
                    'placeholder' => 'Téléphone',
                )
            ])
            ->add('Object', ChoiceType::class, [
                'label' => 'Objet',
                'placeholder' => 'Choisisser l\'objet de votre demande',
                'choices' => [
                    'Je suis un professionnel de la formation' => 'Je suis un professionnel de la formation',
                    'Je cherche un poste en entreprise' => 'Je cherche un poste en entreprise',
                    'Je veux devenir formateur' => 'Je veux devenir formateur',
                    'Je veux devenir partenaire commercial' => 'Je veux devenir partenaire commercial',
                    'Autres (Merci de le préciser dans le message)' => 'Autres',
                ]
            ])
            ->add('message', TextareaType::class, [                
                'label' => 'Message',
                'attr' => array(
                    'placeholder' => 'Message'
                )
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer votre demande de contact',
                'attr' => ['class' => 'save btn-primary'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
