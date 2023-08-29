<?php

namespace App\Controller\Admin;

use App\Entity\FaqTeachers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FaqTeachersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FaqTeachers::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('question'),
            TextField::new('answer'),
        ];
    }
    
}
