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
        $now = new \DateTime(); // Pour obtenir la date actuelle
        
        return [
            IdField::new('id')->onlyOnIndex()->setLabel('ID'),
            AssociationField::new('client')->setLabel('Client'),
            AssociationField::new('student')->setLabel('Étudiant'),
            AssociationField::new('course')->setLabel('Cours'),
            AssociationField::new('user')->setLabel('Utilisateur'),
            
            // Date de début avec valeur par défaut
            DateField::new('beginAt')
                ->setLabel('Date de début'),
            
            // Date de fin avec valeur par défaut
            DateField::new('endAt')
                ->setLabel('Date de fin'),
            
            // Heure de début avec valeur par défaut
            ChoiceField::new('startTime')
                ->setLabel('Heure de début')
                ->setChoices([
                    '8h00' => '8h00',
                    '8h15' => '8h15',
                    '8h30' => '8h30',
                    '8h45' => '8h45',
                    '09h00' => '09h00',
                    '09h15' => '09h15',
                    '09h30' => '09h30',
                    '09h45' => '09h30',
                    '10h00' => '10h00',
                    '10h15' => '10h15',
                    '10h30' => '10h30',
                    '10h45' => '10h45',
                    '11h00' => '11h00',
                    '13h00' => '13h00',
                    '13h15' => '13h15',
                    '13h30' => '13h30',
                    '13h45' => '13h45',
                    '14h00' => '14h00',
                    '14h15' => '14h15',
                    '16h45' => '16h45',
                ])
                ->setFormTypeOption('data', '09h00'),
            
            // Plage horaire avec valeur par défaut
            ChoiceField::new('scheduleTime')
                ->setLabel('Plage horaire')
                ->setChoices([
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
                ])
                ->setFormTypeOption('data', '9h00-12h00 / 13h00-17h00'),
            
            // Nombre d'heures avec valeur par défaut
            ChoiceField::new('hours')
                ->setLabel('Nombre d\'heures')
                ->setChoices([
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
                ])
                ->setFormTypeOption('data', '7'),
            
            // Type d'intervention avec valeur par défaut
            ChoiceField::new('intervention')
                ->setLabel('Type d\'intervention')
                ->setChoices([
                    'Présentiel' => 'Présentiel',
                    'Hybride' => 'Hybride',
                    'Distanciel' => 'Distanciel',
                ])
                ->setFormTypeOption('data', 'Présentiel'),
            
            NumberField::new('remuneration')
            ->setLabel('Rémunération')
            ->setRequired(true) // Si tu veux rendre ce champ obligatoire
            ->setNumDecimals(2), // Pour autoriser les valeurs décimales
            
            NumberField::new('hourlyRate')
            ->setLabel('Taux horaire')
            ->setRequired(true) // Si le champ est obligatoire
            ->setNumDecimals(2)
            ->setHelp('Exemple de syntaxe : 65.4'),
            
            TextField::new('orderNumber')->setLabel('Numéro de commande'),
            TextField::new('missionReference')->setLabel('Référence de la mission'),
            BooleanField::new('teacherPaid')->setLabel('Professeur payé'),
            BooleanField::new('clientPaid')->setLabel('Client payé'),
            BooleanField::new('invoiceSent')->setLabel('Facture envoyée'),
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
