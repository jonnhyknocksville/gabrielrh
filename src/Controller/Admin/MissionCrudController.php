<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use App\Service\MailService;
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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MissionCrudController extends AbstractCrudController
{

    private $entityManager;
    private $mailService;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, MailService $mailService, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->mailService = $mailService;
        $this->logger = $logger;
    }

    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Mission) {
            return;
        }

        // Récupérer les informations de la mission supprimée
        $missionInfo = $this->formatMissionInfo($entityInstance);

        // Générer la requête SQL de réinsertion
        $sqlInsertQuery = $this->generateInsertQuery($missionInfo);

        // Tentative d'envoi de l'email
        try {
            $this->mailService->sendMail(
                [
                    'mission' => $missionInfo,
                    'insertQuery' => $sqlInsertQuery // Passer la requête SQL au template
                ],
                'samihhabbani@gmail.com',
                'Suppression de mission',
                'emails/mission_deleted.html.twig'
            );
            $this->logger->info('Email sent successfully for deleted mission.');
        } catch (\Exception $e) {
            $this->logger->error('Failed to send email for mission deletion', [
                'error' => $e->getMessage()
            ]);
        }

        $this->logger->info('Suppression de la mission ID: ' . $entityInstance->getId());


        // Suppression de l'entité
        $entityManager->remove($entityInstance);
        $entityManager->flush(); // Confirmation de la suppression
    }


    private function generateInsertQuery(array $missionInfo): string
    {
        $columns = implode(", ", array_keys($missionInfo));
        $values = implode(", ", array_map(fn($value) => is_null($value) ? 'NULL' : "'{$value}'", $missionInfo));

        return "INSERT INTO mission ($columns) VALUES ($values);";
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Mission) {
            $this->logger->warning('updateEntity: Entity is not an instance of Mission.');
            return;
        }
    
        $this->logger->info('updateEntity called for mission ID: ' . $entityInstance->getId());
    
        // Obtenir l'Unité de Travail pour détecter les changements
        $unitOfWork = $entityManager->getUnitOfWork();
        $unitOfWork->computeChangeSets(); // Calculer les changements en attente
    
        // Récupérer les changements pour l'entité en cours
        $changeSet = $unitOfWork->getEntityChangeSet($entityInstance);
    
        // Traiter les changements et enregistrer le log
        $formattedChanges = [];
        foreach ($changeSet as $field => $change) {
            list($originalValue, $newValue) = $change; // Récupérer les valeurs originales et nouvelles
    
            // Formatage des valeurs selon leur type
            $formattedOriginal = $this->formatValue($originalValue);
            $formattedNew = $this->formatValue($newValue);
    
            $formattedChanges[$field] = [
                'old' => $formattedOriginal,
                'new' => $formattedNew,
            ];
    
            $this->logger->info("Comparaison du champ {$field}", [
                'original' => $formattedOriginal,
                'new' => $formattedNew
            ]);
        }
    
        // Vérifier les changements et envoyer l'email si des changements sont détectés
        if (!empty($formattedChanges)) {
            $this->logger->info('Sending email for updated mission');
            $this->mailService->sendMail(
                [
                    'changes' => $formattedChanges,
                    'missionId' => $entityInstance->getId() // Passer l'ID de la mission
                ],
                'samihhabbani@gmail.com',
                'Modification de mission',
                'emails/mission_updated.html.twig'
            );
            $this->logger->info('Email sent successfully.');
        } else {
            $this->logger->info('No changes detected, email not sent.');
        }
    
        // Appel de la méthode parent pour finaliser la mise à jour
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Formate la valeur pour l'affichage ou l'enregistrement
     */
    private function formatValue($value)
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('d/m/Y H:i:s');
        } elseif (is_bool($value)) {
            return $value ? 'Oui' : 'Non';
        } elseif (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        } elseif (is_array($value)) {
            return implode(', ', $value);
        }

        return $value !== null ? $value : 'N/A';
    }

    private function detectChanges(array $originalData, array $updatedData): array
    {
        $changes = [];

        foreach ($originalData as $field => $originalValue) {
            $newValue = $updatedData[$field];

            // Convertir les valeurs en float si c'est un champ numérique pour assurer la comparaison correcte
            if (in_array($field, ['remuneration', 'hourlyRate'])) {
                $originalValue = (float) $originalValue;
                $newValue = (float) $newValue;
            }

            // Log pour voir les valeurs et aider au débogage
            $this->logger->info("Comparaison du champ {$field}", [
                'original' => $originalValue,
                'new' => $newValue
            ]);

            // Comparaison non stricte pour gérer les différences de type sans affecter la valeur
            if ($originalValue != $newValue) {
                $changes[$field] = [
                    'old' => $originalValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    private function formatMissionInfo(Mission $mission): array
    {
        return [
            'id' => $mission->getId(),
            'startTime' => $mission->getStartTime(),
            'scheduleTime' => $mission->getScheduleTime(),
            'hours' => $mission->getHours(),
            'intervention' => $mission->getIntervention(),
            'missionReference' => $mission->getMissionReference(),
            'remuneration' => $mission->getRemuneration(),
            'user' => $mission->getUser() ? $mission->getUser()->getId() : null,
            'client' => $mission->getClient() ? $mission->getClient()->getId() : null,
            'course' => $mission->getCourse() ? $mission->getCourse()->getId() : null,
            'beginAt' => $mission->getBeginAt() ? $mission->getBeginAt()->format('Y-m-d H:i:s') : null,
            'endAt' => $mission->getEndAt() ? $mission->getEndAt()->format('Y-m-d H:i:s') : null,
            'timeTable' => $mission->getTimeTable(),
            'student' => $mission->getStudent() ? $mission->getStudent()->getId() : null,
            'hourlyRate' => $mission->getHourlyRate(),
            'nbrDays' => $mission->getNbrDays(),
            'tarification' => $mission->getTarification() ? $mission->getTarification()->getId() : null,
            'orderNumber' => $mission->getOrderNumber(),
            'contractNumber' => $mission->getContractNumber(),
            'codeModule' => $mission->getCodeModule(),
            'teacherPaid' => $mission->isTeacherPaid() ? 1 : 0,
            'clientPaid' => $mission->isClientPaid() ? 1 : 0,
            'invoiceSent' => $mission->isInvoiceSent() ? 1 : 0,
            'description' => $mission->getDescription(),
            'invoiceClient' => $mission->getInvoiceClient() ? $mission->getInvoiceClient()->getId() : null,
            'contract' => $mission->getContract() ? $mission->getContract()->getId() : null,
        ];
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
        // Champs à afficher uniquement sur la page d'index
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                IdField::new('id', 'ID'),
                AssociationField::new('client', 'Client'),
                AssociationField::new('student', 'Étudiant'),
                AssociationField::new('course', 'Cours'),
                AssociationField::new('user', 'Utilisateur'),
                DateField::new('beginAt', 'Date de début'),
                DateField::new('endAt', 'Date de fin'),
                ChoiceField::new('startTime', 'Heure de début')->setChoices([
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
                ChoiceField::new('scheduleTime', 'Plage horaire'),
                NumberField::new('remuneration', 'Rémunération')->setNumDecimals(2),
                NumberField::new('hourlyRate', 'Taux horaire')->setNumDecimals(2),
            ];
        }

        // Champs à afficher lors de la création ou la modification
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            AssociationField::new('client', 'Client'),
            AssociationField::new('student', 'Étudiant'),
            AssociationField::new('course', 'Cours'),
            AssociationField::new('user', 'Utilisateur'),
            DateField::new('beginAt', 'Date de début'),
            DateField::new('endAt', 'Date de fin'),
            TextField::new('titleMission', 'Titre de la mission')
            ->setFormTypeOption('empty_data', 'Prestations de formation'),
            ChoiceField::new('startTime', 'Heure de début')
                ->setChoices([
                    '8h00' => '8h00',
                    '8h15' => '8h15',
                    '8h30' => '8h30',
                    '8h45' => '8h45',
                    '09h00' => '09h00',
                    '09h15' => '09h15',
                    '09h30' => '09h30',
                    '09h45' => '09h45',
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
                    '14h30' => '14h30',
                    '15h30' => '15h30',
                    '16h30' => '16h30',
                    '16h45' => '16h45',
                ]),
            ChoiceField::new('scheduleTime', 'Plage horaire')
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
                    '09h00-17h00' => '09h00-17h00',
                    '10h00-13h00' => '10h00-13h00',
                    '10h00-15h00' => '10h00-15h00',
                    '10h00-17h00' => '10h00-17h00',
                    '09h30-13h00' => '09h30-13h00',
                    '13h00-15h30' => '13h00-15h30',
                    '13h30-15h30' => '13h30-15h30',
                    '13h00-17h00' => '13h00-17h00',
                    '13h45-17h00' => '13h45-17h00',
                    '13h00-18h15' => '13h00-18h15',
                    '13h00-19h00' => '13h00-19h00',
                    '13h30-17h00' => '13h30-17h00',
                    '14h00-17h00' => '14h00-17h00',
                    '14h00-17h30' => '14h00-17h30',
                    '14h00-18h00' => '14h00-18h00',
                    '14h00-19h00' => '14h00-19h00',
                    '15h30-18h30' => '15h30-18h30',
                ]),
            ChoiceField::new('hours', 'Nombre d\'heures')
                ->setChoices([
                    '1' => '1',
                    '1.5' => '1.5',
                    '2' => '2',
                    '2.5' => '2.5',
                    '3' => '3',
                    '3.5' => '3.5',
                    '4' => '4',
                    '4.5' => '4.5',
                    '5' => '5',
                    '5.5' => '5.5',
                    '6' => '6',
                    '6.5' => '6.5',
                    '7' => '7',
                    '7.5' => '7.5',
                    '8' => '8',
                    '8.5' => '8.5',
                    '9' => '9',
                    '9.5' => '9.5',
                    '10' => '10',
                    '10.5' => '10.5',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21'
                ]),
            ChoiceField::new('intervention', 'Type d\'intervention')
                ->setChoices([
                    'Présentiel' => 'Présentiel',
                    'Hybride' => 'Hybride',
                    'Distanciel' => 'Distanciel',
                ]),
            NumberField::new('remuneration', 'Rémunération')->setNumDecimals(2),
            NumberField::new('hourlyRate', 'Taux horaire')->setNumDecimals(2),
            TextField::new('orderNumber', 'Numéro de commande'),
            TextField::new('contractNumber', 'Numéro de contrat'),
            TextField::new('codeModule', 'Code module'),
            TextField::new('missionReference', 'Référence de la mission'),
            BooleanField::new('teacherPaid', 'Professeur payé'),
            BooleanField::new('clientPaid', 'Client payé'),
            BooleanField::new('invoiceSent', 'Facture envoyée'),
            BooleanField::new('invoiceSent', 'Facture envoyée'),
            AssociationField::new('invoice_client', 'Client à qui facturer'),
        ];
    }





    public function configureActions(Actions $actions): Actions
    {
        $cloneAction = Action::new('clone', 'Dupliquer', 'fa fa-clone')
            ->linkToCrudAction('cloneEntity');

        return $actions
            ->add(Crud::PAGE_INDEX, $cloneAction)
            ->add(Crud::PAGE_DETAIL, $cloneAction)
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->linkToCrudAction('updateEntity');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->linkToCrudAction('updateEntity');
            });
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