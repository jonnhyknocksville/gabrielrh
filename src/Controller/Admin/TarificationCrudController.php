<?php

namespace App\Controller\Admin;

use App\Entity\Tarification;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TarificationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tarification::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('promotion'),
            AssociationField::new('client'),
            TextField::new('hourlyRate'),
            TextField::new('dailyRate'),
        ];
    }
    
}
