<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UserCrudController extends AbstractCrudController
{
    private $userPasswordHasher;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicateAction = Action::new('duplicate', 'Dupliquer')
            ->linkToCrudAction('duplicateUser')
            ->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicateAction)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextField::new('email', 'Email'),
            TextField::new('siret', 'SIRET'),
            TextField::new('phone', 'Téléphone'),
        ];

        if ($pageName !== Crud::PAGE_INDEX) {
            $fields = array_merge($fields, [
                TextField::new('naf', 'NAF')->setRequired(false)->setEmptyData(null),
                TextField::new('company', 'Entreprise')->setRequired(false),
                TextField::new('address', 'Adresse'),
                TextField::new('postalCode', 'Code Postal'),
                TextField::new('city', 'Ville'),
                TextField::new('legalForm', 'Forme Juridique'),
                TextField::new('iban', 'Iban'),
                TextField::new('teacher', 'Formateur')->setRequired(false),
                AssociationField::new('courses', 'Cours'),
                ChoiceField::new('roles', 'Rôles')->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Personnel' => 'ROLE_STAFF',
                    'Formateur' => 'ROLE_TEACHER',
                ])->allowMultipleChoices(),
                TextField::new('password', 'Mot de passe')
                    ->setFormType(RepeatedType::class)
                    ->setFormTypeOptions([
                        'type' => PasswordType::class,
                        'first_options' => ['label' => 'Mot de passe'],
                        'second_options' => ['label' => 'Répétez le mot de passe'],
                        'mapped' => false,
                    ])
                    ->setRequired($pageName === Crud::PAGE_NEW)
                    ->onlyOnForms()
            ]);
        }

        return $fields;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword()
    {
        return function ($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $user = (is_null($this->getUser())) ? new User() : $this->getUser();
            $hash = $this->userPasswordHasher->hashPassword($user, $password);
            $form->getData()->setPassword($hash);
        };
    }

    public function duplicateUser(AdminContext $context)
    {
        $user = $context->getEntity()->getInstance();
        if (!$user instanceof User) {
            throw new \LogicException('Entity is not a valid User.');
        }

        $newUser = clone $user;
        $newUser->setEmail($user->getEmail() . '_copy'); // Modification nécessaire pour éviter la duplication d'email

        // Modifiez les propriétés du clone si nécessaire

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        $this->addFlash('success', 'Utilisateur dupliqué avec succès.');

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)
            ->setAction('index')
            ->generateUrl());
    }
}
