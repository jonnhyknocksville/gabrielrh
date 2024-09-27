<?php

namespace App\Controller\Admin;


use App\Entity\Advantages;
use App\Entity\Clients;
use App\Entity\Contact;
use App\Entity\Courses;
use App\Entity\FaqTeachers;
use App\Entity\JobApplication;
use App\Entity\Jobs;
use App\Entity\Mission;
use App\Entity\ProfessionalsNeeds;
use App\Entity\StaffApplication;
use App\Entity\Students;
use App\Entity\Tarification;
use App\Entity\TeacherApplication;
use App\Entity\Themes;
use App\Entity\Training;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;


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

    #[Route('/admin/import', name: 'admin_import_missions')]
    public function importMissions(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST') && $request->files->get('excel_file')) {
            $file = $request->files->get('excel_file');


            // Charger le fichier Excel
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
            } catch (\Exception $e) {
                return new Response('Erreur lors du chargement du fichier : ' . $e->getMessage());
            }

            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            foreach ($rows as $index => $row) {
                // Sauter la première ligne (en-tête)
                if ($index <= 1) {
                    continue;
                }

                [$clientName, $commercial_name, $city, $promotion, $courseTitle, $formateurFullName, $beginAt, $endAt, $startTime, $scheduleTime, $hours, $intervention, $hourlyRateCustomer, $hourlyRateTeacher] = $row;


                // Remplacement des virgules par des points pour les nombres décimaux
                $hours = str_replace(',', '.', $hours);
                $hourlyRateCustomer = str_replace(',', '.', $hourlyRateCustomer);
                $hourlyRateTeacher = str_replace(',', '.', $hourlyRateTeacher);

                // Utiliser convertExcelDate pour les dates avec l'ajout de "00:00:00"
                $beginAtDate = $this->convertExcelDate($beginAt, '00:00:00');
                $endAtDate = $this->convertExcelDate($endAt, '00:00:00');

                // Vérifier si la ligne est vide (par exemple, vérification des champs obligatoires)
                if (empty($clientName) && empty($beginAt) && empty($endAt)) {
                    // Sauter cette ligne si elle est vide
                    continue;
                }

                if ($beginAtDate === false || $endAtDate === false) {
                    return new Response("Erreur dans le format des dates à la ligne " . ($index + 1));
                }

                // Vérifier ou créer le client avec une recherche insensible à la casse et suppression des espaces
                if (!empty($commercial_name)) {
                    // Si le nom commercial est présent, on fait la recherche avec le nom, le nom commercial, et la ville
                    $query = $em->createQuery('
                        SELECT c 
                        FROM App\Entity\Clients c 
                        WHERE LOWER(TRIM(c.name)) = LOWER(TRIM(:name))
                        AND LOWER(TRIM(c.commercialName)) = LOWER(TRIM(:commercialName))
                        AND LOWER(TRIM(c.city)) = LOWER(TRIM(:city))
                    ');
                    $query->setParameter('name', $clientName);
                    $query->setParameter('commercialName', $commercial_name);
                    $query->setParameter('city', $city);
                } else {
                    // Sinon, on fait la recherche avec juste le nom et la ville
                    $query = $em->createQuery('
                        SELECT c 
                        FROM App\Entity\Clients c 
                        WHERE LOWER(TRIM(c.name)) = LOWER(TRIM(:name))
                        AND LOWER(TRIM(c.city)) = LOWER(TRIM(:city))
                    ');
                    $query->setParameter('name', $clientName);
                    $query->setParameter('city', $city);
                }

                $existingClients = $query->getResult();

                // Si un client existant est trouvé, on l'utilise, sinon on en crée un nouveau
                if (count($existingClients) > 0) {
                    $client = $existingClients[0]; // Utiliser le premier client trouvé
                } else {
                    $client = new Clients();
                    $client->setName($clientName);
                    $client->setCity($city); // Assurez-vous de définir la ville si elle n'est pas définie

                    // Si le nom commercial est fourni, on l'ajoute également
                    if (!empty($commercial_name)) {
                        $client->setCommercialName($commercial_name);
                    }

                    $em->persist($client);
                    $em->flush();
                }

                // Séparer le nom complet du formateur en prénom et nom
                $formateurParts = explode(' ', trim($formateurFullName), 2); // Diviser le nom en 2 parties au maximum
                if (count($formateurParts) < 2) {
                    return new Response("Erreur : Le formateur à la ligne " . ($index + 1) . " n'a pas de nom complet (prénom et nom)");
                }
                $formateurFirstName = $formateurParts[0];
                $formateurLastName = $formateurParts[1];

                // Vérifier ou créer le formateur (User) à partir du prénom et du nom
                $formateur = $em->getRepository(User::class)->findOneBy([
                    'firstName' => $formateurFirstName,
                    'lastName' => $formateurLastName,
                ]);

                if (!$formateur) {
                    // Si l'utilisateur n'existe pas, tu peux soit lever une erreur soit créer un nouvel utilisateur
                    $formateur = new User();
                    $formateur->setFirstName($formateurFirstName);
                    $formateur->setLastName($formateurLastName);
                    $formateur->setEmail(strtolower($formateurFirstName . '.' . $formateurLastName . '@exemple.com')); // Mettre un email fictif ou une logique différente
                    $em->persist($formateur);
                }

                // Vérifier ou créer le cours
                $course = $em->getRepository(Courses::class)->findOneBy(['title' => $courseTitle]);
                if (!$course) {
                    $course = new Courses();
                    $course->setTitle($courseTitle);
                    $course->setShowOnWeb(false);
                    $em->persist($course);
                }

                // Vérifier ou créer le student avec une recherche insensible à la casse et suppression des espaces
                $query = $em->createQuery('
                SELECT s 
                FROM App\Entity\Students s 
                WHERE LOWER(TRIM(s.student)) = LOWER(TRIM(:student))
                AND s.hourlyPrice = :hourlyPrice
                AND s.client = :client
                ');
                $query->setParameter('student', $promotion); // Promotion est utilisé comme nom de l'étudiant
                $query->setParameter('hourlyPrice', $hourlyRateCustomer);
                $query->setParameter('client', $client); // Passez l'objet client directement
                $existingStudents = $query->getResult();

                // Si un étudiant existant est trouvé, on l'utilise, sinon on en crée un nouveau
                if (count($existingStudents) > 0) {
                    $student = $existingStudents[0]; // Utiliser le premier étudiant trouvé
                } else {
                    $student = new Students();
                    $student->setClient($client);
                    $student->setStudent($promotion); // Promotion devient le nom de l'étudiant
                    $student->setHourlyPrice($hourlyRateCustomer);
                    $student->setDailyPrice($hourlyRateCustomer * 7);
                    $em->persist($student);
                    $em->flush();
                }

                // Créer la mission
                $mission = new Mission();
                $mission->setClient($client);
                $mission->setUser($formateur);
                $mission->setCourse($course);
                $mission->setBeginAt($beginAtDate); // Utiliser la date convertie
                $mission->setEndAt($endAtDate); // Utiliser la date convertie
                $mission->setStartTime($startTime);
                $mission->setScheduleTime($scheduleTime);
                $mission->setHours($hours);
                $mission->setIntervention($intervention);
                $mission->setHourlyRate($hourlyRateTeacher);
                $mission->setRemuneration($hourlyRateTeacher * $hours);
                $mission->setInvoiceSent(false);
                $mission->setClientPaid(false);
                $mission->setTeacherPaid(false);
                $mission->setStudent($student);
                $em->persist($mission);
            }

            // Sauvegarder toutes les entités dans la base de données
            $em->flush();

            return new Response('Importation réussie !');
        }

        return $this->render('admin/import.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Fws');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Nos domaines d\'intervention');
        yield MenuItem::linkToCrud('Nos formations', 'fas fa-list', Training::class);
        yield MenuItem::linkToCrud('Nos cours', 'fas fa-list', Courses::class);
        yield MenuItem::linkToCrud('Nos thématiques', 'fas fa-list', Themes::class);


        yield MenuItem::section('Nos offres actuelles');
        yield MenuItem::linkToCrud('Nos offres', 'fas fa-list', Jobs::class);
        yield MenuItem::linkToCrud('Advantages', 'fas fa-list', Advantages::class);
        yield MenuItem::linkToCrud('FAQ', 'fas fa-list', FaqTeachers::class);
        yield MenuItem::linkToCrud('Candidatures spontanée', 'fas fa-list', TeacherApplication::class);
        yield MenuItem::linkToCrud('Réponses à une offre', 'fas fa-list', JobApplication::class);

        yield MenuItem::section('Staff');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Missions', 'fas fa-list', Mission::class);
        yield MenuItem::linkToCrud('StaffApplication', 'fas fa-list', StaffApplication::class);

        yield MenuItem::section('Clients');
        yield MenuItem::linkToCrud('Besoins pros', 'fas fa-list', ProfessionalsNeeds::class);
        yield MenuItem::linkToCrud('Clients', 'fas fa-list', Clients::class);
        yield MenuItem::linkToCrud('Etudiants', 'fas fa-list', Students::class);
        // yield MenuItem::linkToCrud('Tarifications', 'fas fa-list', Tarification::class);

        yield MenuItem::section('Prise de contact');
        yield MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class);

        yield MenuItem::section('Importation');
        yield MenuItem::linkToRoute('Importer Missions', 'fas fa-file-import', 'admin_import_missions');

    }

    /**
     * Convertit une date de type texte venant d'Excel au format "d/m/Y"
     * @param string $date La date au format texte (exemple : "07/09/2024")
     * @param string $timeToAdd L'heure à ajouter à la date (exemple : "00:00:00")
     * @return DateTime|false
     */
    private function convertExcelDate($date, $timeToAdd = '00:00:00')
    {
        try {
            // Tenter de créer l'objet DateTime avec le format "d/m/Y"
            $formattedDate = DateTime::createFromFormat('d/m/Y', $date);
            if ($formattedDate) {
                // Ajouter l'heure à la date
                return new DateTime($formattedDate->format('Y-m-d') . ' ' . $timeToAdd);
            } else {
                // Si la date n'a pas pu être convertie
                return false;
            }
        } catch (\Exception $e) {
            return false; // Échec de conversion
        }
    }
}
