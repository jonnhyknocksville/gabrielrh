<?php

namespace App\Form;

use App\Entity\Former;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FormerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,array('label'=>'Nom'))
            ->add('lastName',TextType::class,array('label'=>'Prénom'))
            ->add('Email', EmailType::class,array('label'=>'E-mail'))
            ->add('phone',NumberType::class,array('label'=>'Téléphone'))
            ->add('adress', TextType::class,array('label'=>'Adresse'))
            ->add('city', TextType::class,array('label'=>'Ville'))
            ->add('purpose' ,ChoiceType::class, [ 'label'=>'Motif',
                'choices'  => [
                    'Je cherche un poste de formateur en ligne' => 'formateur en ligne',
                    'Je cherche un poste de fromateur en présentiel' => 'formateur presentiel',
                    'Je veux devenir formateur' => 'devenir formateur',
                    'Autre' => 'autre',
                ],
            ])

            // can only send PDF, PNG, JPG, JPEG in file form
              ->add('CV', FileType::class,['constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*',
                        'application/pdf',
                        'application/x-pdf',
                    ],
                    'mimeTypesMessage' => 'Le fichier n\'est pas valide, assurez vous d\'avoir un fichier au format PDF, PNG, JPG, JPEG)',
                ]),
            ]
        ])
            ->add('message',TextType::class,array('label'=>'Méssage'))
            ->add('Envoyer', SubmitType::class,)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Former::class,
        ]);
    }
}
