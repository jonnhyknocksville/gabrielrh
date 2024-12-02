<?php

namespace App\Controller\Admin;

use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClientsCrudController extends AbstractCrudController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public static function getEntityFqcn(): string
    {
        return Clients::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicateAction = Action::new('duplicate', 'Dupliquer')
            ->linkToCrudAction('duplicateClient')
            ->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicateAction)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom')->onlyOnIndex(),
            TextField::new('commercialName', 'Nom Commercial')->onlyOnIndex(),
            TextField::new('address', 'Adresse')->onlyOnIndex(),
            TextField::new('city', 'Ville')->onlyOnIndex(),
            TextField::new('postalCode', 'Code Postal')->onlyOnIndex(),
            TextField::new('personInCharge', 'Responsable Pédagogique')->onlyOnIndex(),
            TextField::new('representative', 'Représentant')->onlyOnIndex(),
            TextField::new('nbrAgrement', 'Numéro d\'agrément')->onlyOnIndex(),
            TextField::new('phone', 'Téléphone')->onlyOnIndex(),

            // Les champs suivants sont disponibles uniquement dans les formulaires de création/édition
            TextField::new('name', 'Nom')->onlyOnForms(),
            TextField::new('accountServiceName', 'Nom du Service comptable')->onlyOnForms(),
            TextField::new('commercialName', 'Nom Commercial')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('address', 'Adresse')->onlyOnForms(),
            TextField::new('city', 'Ville')->onlyOnForms(),
            TextField::new('postalCode', 'Code Postal')->onlyOnForms(),
            TextField::new('personInCharge', 'Responsable Pédagogique')->onlyOnForms(),
            TextField::new('emailPersonInCharge', 'Email du responsable pédagogique')->onlyOnForms(),
            TextField::new('phonePersonInCharge', 'Téléphone Responsable pédagogique')->onlyOnForms(),
            TextField::new('representative', 'Représentant')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('emailRepresentative', 'Email du directeur/directrice')->onlyOnForms(),
            TextField::new('phoneRepresentative', 'Téléphone Directeur/Directrice')->onlyOnForms(),
            TextField::new('accountantFullName', 'Responsable Facturation')->onlyOnForms(),
            TextField::new('accountantEmail', 'Email Responsable Facturation')->onlyOnForms(),
            ArrayField::new('emailContactToAdd', 'Personnes à ajouter à la facturation')->hideOnIndex(),
            TextField::new('nbrAgrement', 'Numéro d\'agrément')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('backgroundColor', 'Couleur de fond')->onlyOnForms(),
            TextField::new('siret', 'SIRET')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('naf', 'NAF')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('missionAddress', 'Adresse de la Mission')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('missionClient', 'Client de la Mission')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('missionPostalCode', 'Code Postal de la Mission')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
            TextField::new('missionCity', 'Ville de la Mission')->setRequired(false)->setEmptyData(null)->onlyOnForms(),
        ];
    }

    public function duplicateClient(AdminContext $context): RedirectResponse
    {
        /** @var Clients $client */
        $client = $context->getEntity()->getInstance();

        $newClient = clone $client;
        $newClient->setName($client->getName() . ' (Copie)');

        $this->entityManager->persist($newClient);
        $this->entityManager->flush();

        $this->addFlash('success', 'Client dupliqué avec succès.');

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)
            ->setAction('index')
            ->generateUrl());
    }

}
