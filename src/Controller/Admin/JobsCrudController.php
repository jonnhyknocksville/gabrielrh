<?php

namespace App\Controller\Admin;

use App\Entity\Jobs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Jobs::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('city'),
            TextField::new('salary'),
            TextField::new('contract'),
            ArrayField::new('description'),
            TextField::new('titleDescription'),
            TextField::new('location'),
            DateField::new('date'),
            DateField::new('updatedAt'),
            TextField::new('schedule'),
            BooleanField::new('available'),
            TextField::new('missionDescription'),
            ArrayField::new('mainMissions'),
            TextField::new('profileDescription'),
            ArrayField::new('profileRequirements'),
            ArrayField::new('informations'),
            AssociationField::new('advantages'),
            AssociationField::new('category'),
        ];
    }
}
