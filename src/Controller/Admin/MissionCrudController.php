<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MissionCrudController extends AbstractCrudController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
                '8h30' => '8h30',
                '8h45' => '8h45',
                '09h00' => '09h00',
                '09h30' => '09h30',
                '10h00' => '10h00',
                '13h00' => '13h00',
                '13h30' => '13h30',
                '13h45' => '13h45',
                '14h00' => '14h00',
                '16h45' => '16h45',
            ]),
            ChoiceField::new('scheduleTime')->setChoices([
                '8h00-12h00 / 13h00-17h00' => '8h00-12h00 / 13h00-17h00',
                '8h30-12h30 / 13h30-17h30' => '8h30-12h30 / 13h30-17h30',
                '9h00-12h00 / 13h00-17h00' => '9h00-12h00 / 13h00-17h00',
                '9h00-12h00 / 13h00-17h30' => '9h00-12h00 / 13h00-17h30',
                '9h00-12h30 / 13h30-17h00' => '9h00-12h30 / 13h30-17h00',
                '10h00-12h30 / 13h30-17h00' => '10h00-12h30 / 13h30-17h00',
                '13h30-17h00 / 13h00-17h00' => '13h30-17h00 / 13h00-17h00',
                '08h00-12h00' => '08h00-12h00',
                '08h30-12h00' => '08h30-12h00',
                '08h45-12h00' => '08h45-12h00',
                '08h45-16h15' => '08h45-16h15',
                '08h30-12h30' => '08h30-12h30',
                '08h00-13h00' => '08h00-13h00',
                '08h30-13h00' => '08h30-13h00',
                '09h00-12h30' => '09h00-12h30',
                '09h00-12h00' => '09h00-12h00',
                '09h00-13h00' => '09h00-13h00',
                '10h00-13h00' => '10h00-13h00',
                '10h00-15h00' => '10h00-15h00',
                '10h00-17h00' => '10h00-17h00',
                '09h30-13h00' => '09h30-13h00',
                '13h00-15h30' => '13h00-15h30',
                '13h00-17h00' => '13h00-17h00',
                '13h45-17h00' => '13h45-17h00',
                '13h00-18h15' => '13h00-18h15',
                '13h00-19h00' => '13h00-19h00',
                '13h30-17h00' => '13h30-17h00',
                '14h00-17h00' => '14h00-17h00',
                '14h00-18h00' => '14h00-18h00',
                '14h00-19h00' => '14h00-19h00',
            ]),
            ChoiceField::new('hours')->setChoices([
                '1' => '1',
                '1.5' => '1.5',
                '2' => '2',
                '2.5' => '2.5',
                '3' => '3',
                '3.25' => '3.25',
                '3.5' => '3.5',
                '3.75' => '3.75',
                '4' => '4',
                '4.5' => '4.5',
                '5' => '5',
                '5.5' => '5.5',
                '6' => '6',
                '6.5' => '6.5',
                '7' => '7',
                '7.5' => '7.5',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '18' => '18',
                '503' => '503',

            ]),
            ChoiceField::new('intervention')->setChoices([
                'Présentiel' => 'Présentiel',
                'Hybride' => 'Hybride',
                'Distanciel' => 'Distanciel',
            ]),
            TextField::new('missionReference'),
            NumberField::new('nbrDays')->setRequired(false)->setEmptyData(null),
            ChoiceField::new('remuneration')->setChoices([
                '67.5' => '67.5',
                '72' => '72',
                '100' => '100',
                '105' => '105',
                '110' => '110',
                '115' => '115',
                '120' => '120',
                '122.5' => '122.5',
                '125' => '125',
                '130' => '130',
                '132' => '132',
                '135' => '135',
                '140' => '140',
                '145' => '145',
                '150' => '150',
                '155' => '155',
                '157.5' => '157.5',
                '160' => '160',
                '162' => '162',
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
                '375' => '375',
                '400' => '400',
                '450' => '450',
                '500' => '500',
                '520' => '520',
            ]),
            ChoiceField::new('hourlyRate')->setChoices([
                '4' => '4',
                '20' => '20',
                '22.5' => '22.5',
                '25' => '25',
                '26' => '26',
                '26.66' => '26.66',
                '27.14' => '27.14',
                '28.57' => '28.57',
                '30' => '30',
                '32' => '32',
                '32.8' => '32.8',
                '32.85' => '32.85',
                '33.75' => '33.75',
                '35' => '35',
                '40' => '40',
                '42.8' => '42.8',
                '45' => '45',
                '50' => '50',
                '53.5' => '53.5',
                '54' => '54',
                '55' => '55',
                '57' => '57',
                '60' => '60',
                '64' => '64',
                '65' => '65',
                '70' => '70',
            ]),
            AssociationField::new('tarification'),
            TextField::new('orderNumber'),
            BooleanField::new('teacherPaid'),
            BooleanField::new('clientPaid'),
            BooleanField::new('invoiceSent'),
            TextField::new('background')->setRequired(false)->setEmptyData(null),
            TextareaField::new('description')->setRequired(false)->setEmptyData(null),

        ];
    }



    public function configureActions(Actions $actions): Actions
    {
        $cloneAction = Action::new('clone', 'Dupliquer', 'fa fa-clone')
            ->linkToCrudAction('cloneEntity');

        return $actions
            ->add(Crud::PAGE_INDEX, $cloneAction)
            ->add(Crud::PAGE_DETAIL, $cloneAction);
    }

    public function cloneEntity(AdminContext $context): RedirectResponse
    {
        $entity = $context->getEntity()->getInstance();
        $clone = clone $entity;

        // Modifiez les propriétés du clone si nécessaire

        $this->entityManager->persist($clone);
        $this->entityManager->flush();

        $this->addFlash('success', 'Mission dupliquée avec succès!');

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)
            ->setAction('index')
            ->generateUrl());
    }

}
