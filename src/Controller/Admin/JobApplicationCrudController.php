<?php

namespace App\Controller\Admin;

use App\Entity\JobApplication;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class JobApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobApplication::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextField::new('phone', 'Téléphone'),
            TextField::new('email', 'Email'),
            TextField::new('yearsExperience', "Années d'expérience"),
            TextField::new('mobility', 'Mobilité'),
            TextField::new('cv', 'Curriculum Vitae')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false, // Pour ne pas lier le fichier directement à l'entité
                    'required' => false,
                ])
                ->setLabel('Télécharger CV')
                ->onlyOnForms(),
            DateField::new('updatedAt', 'Date de mise à jour')->hideOnForm(),
            TextField::new('motivation', 'Motivation'),
            TextField::new('diploma', 'Diplôme'),
            AssociationField::new('job', 'Poste lié'),
        ];
    }
}
