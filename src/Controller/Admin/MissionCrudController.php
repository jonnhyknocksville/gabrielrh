<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('client')
            ->add('user')
            ->add('course')
            ->add('student')
            ->add('beginAt')
            ->add('endAt')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('client'),
            AssociationField::new('student'),
            AssociationField::new('course'),
            AssociationField::new('user'),
            DateField::new('beginAt'),
            DateField::new('endAt'),
            ChoiceField::new('timeTable')->setChoices([
                'Matin' => 'Matin',
                'Après-midi' => 'Après-midi',
                'Journée' => 'Journée',
            ]),
            ChoiceField::new('startTime')->setChoices([
                '8h00' => '8h00',
                '09h00' => '09h00',
                '10h00' => '10h00',
                '13h30' => '13h30',
            ]), 
            ChoiceField::new('scheduleTime')->setChoices([
                '8h00-12h00 / 13h00-17h00' => '8h00-12h00 / 13h00-17h00',
                '9h00-12h00 / 13h00-17h00' => '9h00-12h00 / 13h00-17h00',
                '9h00-12h30 / 13h30-17h00' => '9h00-12h30 / 13h30-17h00',
                '13h30-17h00 / 13h00-17h00' => '13h30-17h00 / 13h00-17h00',
                '09h00-12h30' => '09h00-12h30',
                '13h30-17h00' => '13h30-17h00',
            ]),
            ChoiceField::new('hours')->setChoices([
                '1' => '1',
                '1,5' => '1,5',
                '2' => '2',
                '2,5' => '2,5',
                '3' => '3',
                '3,5' => '3,5',
                '4' => '4',
                '4,5' => '4,5',
                '5' => '5',
                '5,5' => '5,5',
                '6' => '6',
                '6,5' => '6,5',
                '7' => '7',
                '7,5' => '7,5',
                '8' => '8'
            ]), 
            ChoiceField::new('intervention')->setChoices([
                'Présentiel' => 'Présentiel',
                'Hybride' => 'Hybride',
                'Distanciel' => 'Distanciel',
            ]),
            TextField::new('missionReference'),
            ChoiceField::new('remuneration')->setChoices([
                '160' => '160',
                '165' => '165',
                '170' => '170',
                '175' => '175',
                '180' => '180',
                '185' => '185',
                '190' => '190',
                '195' => '195',
                '200' => '200',
                '205' => '205',
                '210' => '210',
                '215' => '215',
                '220' => '220',
                '225' => '225',
                '230' => '230',
                '235' => '235',
                '240' => '240',
                '245' => '245',
                '250' => '250',
                '255' => '255',
                '260' => '260',
                '265' => '265',
                '270' => '270',
                '275' => '275',
                '280' => '280',
                '285' => '285',
                '290' => '290',
                '295' => '295',
                '300' => '300',
                '305' => '305',
                '310' => '310',
                '315' => '315',
                '320' => '320',
                '325' => '325',
                '340' => '340',
                '345' => '345',
                '350' => '350',
                '355' => '355',
            ]),
            ChoiceField::new('hourlyRate')->setChoices([
                '20' => '20',
                '25' => '25',
                '30' => '30',
                '35' => '35',
                '40' => '40',
                '45' => '45',
                '50' => '50',
                '55' => '55',
                '60' => '60',
                '65' => '65',
            ])
            // TextField::new('backgroundColor'),
        ];
    }
    
}
