<?php

namespace App\Controller\Admin;

use App\Entity\TeacherApplication;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FileField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class TeacherApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeacherApplication::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextField::new('email', 'Email'),
            TextField::new('phone', 'Téléphone'),
            TextField::new('streetAddress', 'Adresse'),
            TextField::new('city', 'Ville'),
            TextField::new('postalCode', 'Code Postal'),
            TextField::new('cvFile', 'Curriculum Vitae')
                ->setFormType(VichFileType::class)
                ->setLabel('Télécharger CV')
                ->onlyOnForms(),
            DateField::new('updatedAt', 'Date de mise à jour')
                ->hideOnForm(),
            TextField::new('message', 'Message'),
            TextField::new('motif', 'Motif'),
            TextField::new('namerCV', 'Nom du fichier CV')->hideOnIndex(),
        ];
    }
}
