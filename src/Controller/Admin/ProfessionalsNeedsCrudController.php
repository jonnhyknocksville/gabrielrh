<?php

namespace App\Controller\Admin;

use App\Entity\ProfessionalsNeeds;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProfessionalsNeedsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProfessionalsNeeds::class;
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
