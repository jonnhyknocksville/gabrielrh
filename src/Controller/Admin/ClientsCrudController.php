<?php

namespace App\Controller\Admin;

use App\Entity\Clients;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClientsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Clients::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('commercialName')->setRequired(false),
            TextField::new('address'),
            TextField::new('city'),
            TextField::new('postalCode'),
            TextField::new('personInCharge'),
            TextField::new('phone'),
            TextField::new('backgroundColor'),
            TextField::new('siret'),
        ];
    }
    
}
