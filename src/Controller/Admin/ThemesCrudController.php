<?php

namespace App\Controller\Admin;

use App\Entity\Themes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ThemesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Themes::class;
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
