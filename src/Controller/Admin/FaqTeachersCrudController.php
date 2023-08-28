<?php

namespace App\Controller\Admin;

use App\Entity\FaqTeachers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FaqTeachersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FaqTeachers::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
