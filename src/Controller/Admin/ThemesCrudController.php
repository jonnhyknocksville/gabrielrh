<?php

namespace App\Controller\Admin;

use App\Entity\Themes;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ThemesCrudController extends AbstractCrudController
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
        return Themes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextField::new('description', 'Description'),
            ImageField::new('picture', 'Image')
                ->setUploadDir('public/build/')
                ->setBasePath($this->params->get('app.path.themes_images'))
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setRequired(false)
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
            ->setEntityLabelInSingular('Thème')
            ->setEntityLabelInPlural('Thèmes')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des thèmes')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un thème')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un thème')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails du thème')
            ->setPaginatorPageSize(10);
    }

    public function duplicate(EntityManagerInterface $entityManager): RedirectResponse
    {
        /** @var Themes $theme */
        $theme = $this->getContext()->getEntity()->getInstance();

        // Cloner l'entité theme
        $duplicatedTheme = clone $theme;
        $duplicatedTheme->setTitle($theme->getTitle() . ' (Copie)');

        $entityManager->persist($duplicatedTheme);
        $entityManager->flush();

        $this->addFlash('success', 'Le thème a été dupliqué avec succès.');

        $url = $this->adminUrlGenerator->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
