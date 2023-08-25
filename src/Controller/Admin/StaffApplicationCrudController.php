<?php

namespace App\Controller\Admin;

use App\Entity\Jobs;
use App\Entity\StaffApplication;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class StaffApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StaffApplication::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
            AssociationField::new('job'),
            DateField::new('date'),
        ];
    }
    
}
