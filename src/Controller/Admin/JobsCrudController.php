<?php

namespace App\Controller\Admin;

use App\Entity\Jobs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Jobs::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('city')->setLabel("Lieu de l'intervention"),
            ChoiceField::new('salary')->setChoices([
                '150-200' => '150-200',
                '200-250' => '200-250',
                '250-300' => '250-300',
                '300-350' => '300-350',
                '350-400' => '350-400',
                '400-450' => '400-450',
                '450-500' => '450-500',
                '+500' => '+500',
                '+600' => '+600',
            ])->setLabel("TJM"),
            AssociationField::new('course')->setLabel("L'intervention"),
            AssociationField::new('theme')->setLabel("Thème lié à cette mission"),
            ChoiceField::new('contract')->setChoices([
                'freelance' => 'freelance',
                'cdi' => 'cdi',
                'cdd' => 'cdd',
                'autres' => 'autres',
            ])->setLabel("Le type de contrat"),
            ArrayField::new('description')->setLabel("Quelques mots général sur la mission"),
            TextField::new('titleDescription')->setLabel("Titre de la description générale"),
            TextField::new('location')->setLabel("Lieu de l'intervention"),
            DateField::new('date')->setLabel("Date d'intervention"),
            DateField::new('updatedAt')->setLabel("Date de mise à jour"),
            TextField::new('schedule')->setLabel("Horaires de la semaine"),
            BooleanField::new('available')->setLabel("Mission disponible ou non"),
            TextField::new('missionDescription')->setLabel("Introduction aux missions principales"),
            ArrayField::new('mainMissions')->setLabel("Les missions principales"),
            TextField::new('profileDescription')->setLabel("Description du profil recherché"),
            ArrayField::new('profileRequirements')->setLabel("Compétences recherchées"),
            ArrayField::new('informations')->setLabel("Informations supplémentaires sur la missions"),
            AssociationField::new('advantages')->setLabel("Avantages de la mission"),
        ];
    }
}
