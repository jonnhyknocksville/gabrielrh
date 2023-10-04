<?php

namespace App\Controller\Admin;

use App\Entity\Students;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Students::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('client')
            ->add('student')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('student'),
            AssociationField::new('client'),
            TextField::new('hourlyPrice'),
            TextField::new('DailyPrice'),
        ];
    }
    
}
