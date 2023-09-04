<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('client'),
            AssociationField::new('course'),
            AssociationField::new('user'),
            DateField::new('beginAt'),
            DateField::new('endAt'),
            ChoiceField::new('timeTable')->setChoices([
                'Matin' => 'Matin',
                'Après-midi' => 'Après-midi',
                'Journée' => 'Journée',
            ])->setValue('Journée'),
            ChoiceField::new('startTime')->setChoices([
                '8h00' => '8h00',
                '09h00' => '09h00',
                '10h00' => '10h00',
                '13h30' => '13h30',
            ])->setValue('09h00'), 
            ChoiceField::new('scheduleTime')->setChoices([
                '8h00-12h00 / 13h00-17h00' => '8h00-12h00 / 13h00-17h00',
                '9h00-12h00 / 13h00-17h00' => '9h00-12h00 / 13h30-17h00',
                '9h00-12h30 / 13h30-17h00' => '9h00-12h30 / 13h30-17h00',
                '13h30-17h00 / 13h00-17h00' => '13h30-17h00 / 13h00-17h00',
            ])->setValue('9h00-12h30 / 13h30-17h00'),
            TextField::new('hours'),
            ChoiceField::new('intervention')->setChoices([
                'Présentiel' => 'Présentiel',
                'Hybride' => 'Hybride',
                'Distanciel' => 'Distanciel',
            ])->setValue('Présentiel'),
            TextField::new('missionReference'),
            ChoiceField::new('remuneration')->setChoices([
                '160' => '160',
                '180' => '180',
                '200' => '200',
                '220' => '220',
                '240' => '240',
                '260' => '260',
                '280' => '280',
                '300' => '300',
                '320' => '320',
                '340' => '340',
                '350' => '350',
            ])->setValue('220'),
            // TextField::new('backgroundColor'),
        ];
    }
    
}
