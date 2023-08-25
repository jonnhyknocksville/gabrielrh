<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('client'),
            AssociationField::new('course'),
            AssociationField::new('user'),
            DateField::new('beginAt'),
            DateField::new('endAt'),
            TextField::new('startTime'), 
            TextField::new('scheduleTime'),
            TextField::new('hours'),
            TextField::new('intervention'),
            TextField::new('missionReference'),
            TextField::new('remuneration'),
            // TextField::new('backgroundColor'),
        ];
    }
    
}
