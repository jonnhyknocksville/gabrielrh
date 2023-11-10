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
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('commercialName')->setRequired(false)->setEmptyData(null),
            TextField::new('address'),
            TextField::new('city'),
            TextField::new('postalCode'),
            TextField::new('personInCharge'),
            TextField::new('representative')->setRequired(false)->setEmptyData(null),
            TextField::new('nbrAgrement')->setRequired(false)->setEmptyData(null),
            TextField::new('phone'),
            TextField::new('backgroundColor'),
            TextField::new('siret')->setRequired(false)->setEmptyData(null),
            TextField::new('naf')->setRequired(false)->setEmptyData(null),
        ];
    }
    
}
