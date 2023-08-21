<?php

namespace App\Controller\Admin;

use App\Entity\Jobs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class JobsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Jobs::class;
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
