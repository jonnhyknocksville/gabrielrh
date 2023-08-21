<?php

namespace App\Controller\Admin;

use App\Entity\CoursesDetails;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CoursesDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CoursesDetails::class;
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
