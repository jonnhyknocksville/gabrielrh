<?php

namespace App\Controller\Admin;


use App\Entity\Advantages;
use App\Entity\Categories;
use App\Entity\Clients;
use App\Entity\Contact;
use App\Entity\Courses;
use App\Entity\Jobs;
use App\Entity\Mission;
use App\Entity\ProfessionalsNeeds;
use App\Entity\StaffApplication;
use App\Entity\TeacherApplication;
use App\Entity\Themes;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CoursesCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Fws');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Nos cours', 'fas fa-list', Courses::class);
        yield MenuItem::linkToCrud('Nos thématiques', 'fas fa-list', Themes::class);
        yield MenuItem::linkToCrud('Nos offres', 'fas fa-list', Jobs::class);
        yield MenuItem::linkToCrud('Besoins pros', 'fas fa-list', ProfessionalsNeeds::class);
        yield MenuItem::linkToCrud('Candidatures', 'fas fa-list', TeacherApplication::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Nos catégories', 'fas fa-list', Categories::class);
        yield MenuItem::linkToCrud('Advantages', 'fas fa-list', Advantages::class);
        yield MenuItem::linkToCrud('Clients', 'fas fa-list', Clients::class);
        yield MenuItem::linkToCrud('Missions', 'fas fa-list', Mission::class);
        yield MenuItem::linkToCrud('StaffApplication', 'fas fa-list', StaffApplication::class);
    }
}
