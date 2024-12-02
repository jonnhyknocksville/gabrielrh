<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => ['class' => 'text-light'], // Classe pour le label
                'attr' => [
                    'class' => 'text-dark bg-info', // Classe pour l'input
                    'readonly' => true, // Champ non modifiable
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark',
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('siret', TextType::class, [
                'label' => 'SIRET',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('naf', TextType::class, [
                'label' => 'NAF',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('legalForm', TextType::class, [
                'label' => 'Forme juridique',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark bg-info',
                    'readonly' => true,
                ]
            ])
            ->add('iban', TextType::class, [
                'label' => 'IBAN',
                'label_attr' => ['class' => 'text-light'],
                'attr' => [
                    'class' => 'text-dark', // Ce champ est modifiable
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un IBAN valide.',
                    ]),
                    new Length([
                        'max' => 27,
                        'maxMessage' => 'L\'IBAN ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('diplomas', FileType::class, [
                'label' => 'Diplômes',
                'label_attr' => ['class' => 'text-light'],
                'required' => false,
                'data_class' => null,
                'mapped' => false, // Si vous avez un fichier déjà existant
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou ZIP valide.',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1 Mo.',
                    ]),
                ]
            ])
            ->add('cv', FileType::class, [
                'label' => 'CV',
                'label_attr' => ['class' => 'text-light'],
                'required' => false,
                'data_class' => null,
                'mapped' => false, // Si vous avez un fichier déjà existant
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou ZIP valide.',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1 Mo.',
                    ]),
                ]
            ])
            ->add('kbis', FileType::class, [
                'label' => 'Kbis',
                'label_attr' => ['class' => 'text-light'],
                'required' => false,
                'data_class' => null,
                'mapped' => false, // Si vous avez un fichier déjà existant
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou ZIP valide.',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1 Mo.',
                    ]),
                ]
            ])
            ->add('criminalRecord', FileType::class, [
                'label' => 'Casier judiciaire',
                'label_attr' => ['class' => 'text-light'],
                'required' => false,
                'data_class' => null,  // Si vous avez un fichier déjà existant
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou ZIP valide.',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1 Mo.',
                    ]),
                ]
            ])
            ->add('attestationCompetence', FileType::class, [
                'label' => 'Attestation de compétence',
                'label_attr' => ['class' => 'text-light'],
                'required' => false,
                'data_class' => null,  // Si vous avez un fichier déjà existant
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou ZIP valide.',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1 Mo.',
                    ]),
                ]
            ])
            ->add('attestationVigilance', FileType::class, [
                'label' => 'Attestation de vigilance',
                'label_attr' => ['class' => 'text-light'],
                'required' => false,
                'data_class' => null,
                'mapped' => false, // Si vous avez un fichier déjà existant,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou ZIP valide.',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1 Mo.',
                    ]),
                ]
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
