<?php

namespace App\Controller\Admin;

use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TrainingCrudController extends AbstractCrudController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public static function getEntityFqcn(): string
    {
        return Training::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom de la formation'),
            TextField::new('description', 'Description'),
            BooleanField::new('showOnWeb', 'Visible sur le site web'),
            ImageField::new('logo', 'Logo')
                ->setUploadDir('public/build/')
                ->setBasePath($this->params->get('app.path.courses_images'))
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setRequired(false)
                ->hideWhenUpdating(),
            TextField::new('heading', 'Titre'),
            TextField::new('titleIntroduction', 'Titre de l’introduction'),
            ArrayField::new('introduction', 'Introduction')->hideOnIndex(),
            ArrayField::new('objectives', 'Objectifs')->hideOnIndex(),
            ArrayField::new('learningPath', 'Parcours d’apprentissage')->hideOnIndex(),
            ArrayField::new('public', 'Public visé')->hideOnIndex(),
            ArrayField::new('requirements', 'Pré-requis'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Formations') // Titre de la page de liste
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier la Formation') // Titre de la page d'édition
            ->setPageTitle(Crud::PAGE_NEW, 'Créer une Nouvelle Formation') // Titre de la page de création
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails de la Formation'); // Titre de la page de détails
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate', 'Dupliquer')
            ->linkToCrudAction('duplicate')
            ->setCssClass('btn btn-secondary');

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicate)
            ->add(Crud::PAGE_DETAIL, $duplicate);
    }

    public function duplicate(AdminContext $context, EntityManagerInterface $entityManager): RedirectResponse
    {
        /** @var Training $training */
        $training = $context->getEntity()->getInstance();
    
        // Créer une nouvelle instance de Training avec les mêmes valeurs
        $duplicatedTraining = clone $training;
        $duplicatedTraining->setName($training->getName() . ' (Copie)');
    
        $entityManager->persist($duplicatedTraining);
        $entityManager->flush();
    
        // Afficher un message de succès
        $this->addFlash('success', 'La formation a été dupliquée avec succès.');
    
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)
            ->setAction('index')
            ->generateUrl());
    }
}
