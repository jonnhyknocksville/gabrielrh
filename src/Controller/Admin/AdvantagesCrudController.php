<?php

namespace App\Controller\Admin;

use App\Entity\Advantages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdvantagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Advantages::class;
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
