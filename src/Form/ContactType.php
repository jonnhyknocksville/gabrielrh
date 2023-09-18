<?php

namespace App\Form;

use App\Entity\Contact;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ContactType extends AbstractType
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
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-6 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Téléphone'
                )
            ])
            ->add('object', ChoiceType::class, [
                'label' => 'Choisissez un motif', 
                'placeholder' => '--',
                'choices'  => [
                    'Je suis un professionnel de la formation' => 'Je suis un professionnel de la formation',
                    'Je cherche un poste en entreprise' => 'Je cherche un poste en entreprise',
                    'Je veux devenir formateur' => 'Je veux devenir formateur',
                    'Je veux devenir partenaire commercial' => 'Je veux devenir partenaire commercial',
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
            ->add('currentJob', ChoiceType::class, [
                'label' => 'Votre poste actuel', 
                'placeholder' => '--',
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
            ->add('message', TextareaType::class, [
                'label' => 'Ecrivez votre message',
                'row_attr' => [
                    'class' => 'd-flex flex-column col-md-12 mb-3'
                ],
                'attr' => array(
                    'placeholder' => 'Message'
                )
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale' => 'fr',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
