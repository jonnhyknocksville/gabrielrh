<?php

namespace App\Controller\Admin;

use App\Entity\Jobs;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class JobsCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Jobs::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre')->onlyOnIndex(),
            TextField::new('city', "Lieu de l'intervention")->onlyOnIndex(),
            ChoiceField::new('salary', 'TJM')->setChoices([
                '150-200' => '150-200',
                '200-250' => '200-250',
                '250-300' => '250-300',
                '300-350' => '300-350',
                '350-400' => '350-400',
                '400-450' => '400-450',
                '450-500' => '450-500',
                '+500' => '+500',
                '+600' => '+600',
            ])->onlyOnIndex(),
            AssociationField::new('course', "L'intervention")->onlyOnIndex(),
            AssociationField::new('theme', "Thème lié à cette mission")->onlyOnIndex(),
            ChoiceField::new('contract', 'Le type de contrat')->setChoices([
                'freelance' => 'freelance',
                'cdi' => 'cdi',
                'cdd' => 'cdd',
                'autres' => 'autres',
            ])->onlyOnIndex(),
            ArrayField::new('description', 'Quelques mots général sur la mission')->onlyOnIndex(),

            // Les autres champs qui ne seront pas affichés sur la page INDEX
            TextField::new('titleDescription', 'Titre de la description générale')->hideOnIndex(),
            TextField::new('location', "Lieu de l'intervention")->hideOnIndex(),
            DateField::new('date', "Date d'intervention")->hideOnIndex(),
            DateField::new('updatedAt', "Date de mise à jour")->hideOnIndex(),
            TextField::new('schedule', "Horaires de la semaine")->hideOnIndex(),
            BooleanField::new('available', "Mission disponible ou non")->hideOnIndex(),
            TextField::new('missionDescription', "Introduction aux missions principales")->hideOnIndex(),
            ArrayField::new('mainMissions', "Les missions principales")->hideOnIndex(),
            TextField::new('profileDescription', "Description du profil recherché")->hideOnIndex(),
            ArrayField::new('profileRequirements', "Compétences recherchées")->hideOnIndex(),
            ArrayField::new('informations', "Informations supplémentaires sur la mission")->hideOnIndex(),
            AssociationField::new('advantages', "Sélectionner les avantages de la mission")->hideOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicateAction = Action::new('duplicate', 'Dupliquer')
            ->linkToCrudAction('duplicate')
            ->setCssClass('btn btn-secondary');

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicateAction);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Mission')
            ->setEntityLabelInPlural('Missions')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des missions')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer une mission')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une mission')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails de la mission')
            ->setPaginatorPageSize(10);
    }

    public function duplicate(EntityManagerInterface $entityManager): RedirectResponse
    {
        /** @var Jobs $job */
        $job = $this->getContext()->getEntity()->getInstance();

        // Cloner l'entité job
        $duplicatedJob = clone $job;
        $duplicatedJob->setTitle($job->getTitle() . ' (Copie)');

        $entityManager->persist($duplicatedJob);
        $entityManager->flush();

        $this->addFlash('success', 'La mission a été dupliquée avec succès.');

        $url = $this->adminUrlGenerator->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
