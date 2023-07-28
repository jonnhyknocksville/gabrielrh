<?php

namespace App\Form;

use App\Entity\Jobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
                'label' => 'Titre du job',
                'attr' => array(
                    'placeholder' => 'taper le titre du job'
                )                
                
            ])
            ->add('salary', MoneyType::class, [
                'label' => 'Salaire',
                'attr' => array(
                    'placeholder' => 'taper le salaire du job'
                )                
                
            ])
            ->add('published_on', DateType::class, [
                'label' => 'Publié le',
                'widget' => 'single_text'              
                
            ])
            ->add('updated_on', DateType::class, [
                'label' => 'Mise à jour le',
                'widget' => 'single_text'                
                
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => array(
                    'placeholder' => 'taper la description du job'
                )                
                
            ])
            ->add('start_date', DateTimeType::class, [
                'label' => 'Le job débute le',
                'widget' => 'single_text'                
                
            ])
            ->add('end_date', DateTimeType::class, [
                'label' => 'Le job fini le',
                'widget' => 'single_text'                
                
            ])
            ->add('schedule', TextType::class, [
                'label' => 'Horaires',
                'attr' => array(
                    'placeholder' => 'taper les horaires du job'
                )                
                
            ])
            ->add('company', TextType::class, [
                'label' => 'Entreprise',
                'attr' => array(
                    'placeholder' => 'taper le nom de l\'entreprise qui propose le job'
                )                
                
            ])
            ->add('experience', TextType::class, [
                'label' => 'Epérience demandé',
                'attr' => array(
                    'placeholder' => 'taper l\'expérience demandé pour ce job'
                )                
                
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse du job',
                'attr' => array(
                    'placeholder' => 'taper l\'adresse du job'
                )                
                
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'code postal du job',
                'attr' => array(
                    'placeholder' => 'taper le code postal du job'
                )                
                
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville du job',
                'attr' => array(
                    'placeholder' => 'taper la ville du job'
                )                
                
            ])
            ->add('type_of_audience', TextType::class, [
                'label' => 'Type d\'élèves du job',
                'attr' => array(
                    'placeholder' => 'taper le type d\'élèves du job'
                )                
                
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer votre job',
                'attr' => ['class' => 'save btn-primary'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jobs::class,
        ]);
    }
}
