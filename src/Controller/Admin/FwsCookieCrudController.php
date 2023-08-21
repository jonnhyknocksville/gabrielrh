<?php

namespace App\Controller\Admin;

use App\Entity\FwsCookie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FwsCookieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FwsCookie::class;
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
