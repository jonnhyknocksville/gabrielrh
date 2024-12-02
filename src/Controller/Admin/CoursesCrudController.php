<?php

namespace App\Controller\Admin;

use App\Entity\Courses;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CoursesCrudController extends AbstractCrudController
{
    private $params;
    private $adminUrlGenerator;

    public function __construct(ParameterBagInterface $params, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->params = $params;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Courses::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Cours')
            ->setEntityLabelInPlural('Cours')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des cours')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un cours')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un cours')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails du cours')
            ->setPaginatorPageSize(10);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextField::new('description', 'Description'),
            BooleanField::new('showOnWeb', 'Afficher sur le Web'),
            ImageField::new('logo', 'Logo')
                ->setUploadDir('public/build/')
                ->setBasePath($this->params->get('app.path.courses_images'))
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setRequired(false)
                ->hideWhenUpdating(),
            AssociationField::new('theme', 'Thème du cours'),
            TextField::new('heading', 'Titre principal du cours'),
            TextField::new('titleIntroduction', 'Titre d\'introduction'),
            ArrayField::new('introduction', 'Introduction au cours')->hideOnIndex(),
            ArrayField::new('objectives', 'Objectifs du cours')->hideOnIndex(),
            ArrayField::new('learningPath', 'Parcours d\'apprentissage')->hideOnIndex(),
            ArrayField::new('public', 'Public ciblre')->hideOnIndex(),
            ArrayField::new('requirements', 'Pré-requis pour ce cours')
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

    public function duplicate(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        /** @var Courses $course */
        $course = $this->getContext()->getEntity()->getInstance();

        // Cloner l'entité course
        $duplicatedCourse = clone $course;
        $duplicatedCourse->setTitle($course->getTitle() . ' (Copie)');

        $entityManager->persist($duplicatedCourse);
        $entityManager->flush();

        $this->addFlash('success', 'Le cours a été dupliqué avec succès.');

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
