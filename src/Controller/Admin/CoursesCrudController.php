<?php

namespace App\Controller\Admin;

use App\Entity\Courses;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CoursesCrudController extends AbstractCrudController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public static function getEntityFqcn(): string
    {
        return Courses::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            ImageField::new('logo')
            ->setUploadDir('public/')
            ->setBasePath($this->params->get('app.path.courses_images'))
            ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
            ->setRequired(false),
            AssociationField::new('theme'),
            TextField::new('heading'),
            TextField::new('titleIntroduction'),
            ArrayField::new('introduction'),
            ArrayField::new('objectives'),
            ArrayField::new('learningPath'),
            ArrayField::new('public'),
            ArrayField::new('requirements')
        ];
    }
    
}
