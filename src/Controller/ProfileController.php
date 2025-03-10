<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Contract;
use App\Entity\Courses;
use App\Entity\Estimate;
use App\Entity\Invoices;
use App\Entity\Jobs;
use App\Entity\Mission;
use App\Entity\StaffApplication;
use App\Entity\Themes;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use App\Repository\ClientsRepository;
use App\Repository\EstimateRepository;
use App\Repository\InvoicesRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Dompdf\Dompdf;
use Symfony\Component\Filesystem\Filesystem;
class ProfileController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $doctrine): Response
    {

        $data = $this->getValues($doctrine);

        return $this->render('profile/index.html.twig', [
            'year' => $data[0],
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1]
        ]);
    }

    #[Route('/profile/password', name: 'app_profile_password')]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $doctrine,
        EntityManagerInterface $entityManager
    ): Response {

        $data = $this->getValues($doctrine);

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $form['oldPassword']->getData())) {

                // j'encode le mot de passe et je le mets à jour
                $newEncodedPassword = $passwordEncoder->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($newEncodedPassword);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('message', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('app_profile');
            } else {
                $this->addFlash('message', 'La saisie de l\'ancien mot de passe est incorrecte');
            }
        }

        return $this->render('profile/modify-password.html.twig', [
            'changePasswordForm' => $form->createView(),
            'active_link' => 'profile',
            'year' => $data[0],
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1]
        ]);

    }

    #[Route('/profile/contracts', name: 'app_profile_contracts')]
    public function contracts(Request $request, EntityManagerInterface $doctrine): Response
    {

        $data = $this->getValues($doctrine);

        $userId = $this->getUser()->getId();
        $dateTime = new \DateTime("now");

        // Récupérer l'année sélectionnée (ou prendre l'année en cours par défaut)
        $selectedYear = $request->query->get('year', $dateTime->format("Y"));

        // Récupérer les mois distincts des missions
        $missionsMonths = $doctrine->getRepository(Mission::class)->findAnnualMissions($userId, $selectedYear);

        // Récupérer les contrats de l'utilisateur pour l'année donnée
        $contracts = $doctrine->getRepository(Contract::class)->findContractsForUserAndYear($userId, $selectedYear);

        // Transformer les contrats pour un accès facile par mois
        $contractsByMonth = [];
        foreach ($contracts as $contract) {
            $contractMonth = (int) $contract->getDate()->format('m');
            $contractsByMonth[$contractMonth] = $contract;
        }

        // Associer chaque mois de mission au contrat correspondant s'il existe
        foreach ($missionsMonths as &$missionMonth) {
            $month = $missionMonth[1];
            $missionMonth['contract'] = $contractsByMonth[$month] ?? null;
        }

        return $this->render('profile/contracts.html.twig', [
            'missionsMonths' => $missionsMonths,
            'userId' => $userId,
            'year' => $data[0],
            'selectedYear' => $selectedYear,
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1]
        ]);
    }


    /**
     * Méthode permettant de générer une liste de facture pour les intervenants pour qu'ils puissent voir a quoi doit ressembler leur facture
     */
    #[Route('/profile/generate/invoice/type/{year}/{month}', name: 'app_profile_generate_invoice_type')]
    public function generate_invoice_type(
        Request $request,
        EntityManagerInterface $doctrine,
        int $year,
        int $month,
    ): Response {

        $data = $this->getValues($doctrine);
        $idUser = $this->getUser()->getId();
        $invoices = $doctrine->getRepository(Mission::class)->findMonthlyInvoicesToGenerateForUser($year, $month, $idUser);

        $invoicesToShow = NULL;
        $client = null;
        foreach ($invoices as $invoice) {

            $client = $invoice->getInvoiceClient();
            if ($invoice->getBeginAt() == $invoice->getEndAt()) {
                $invoicesToShow[$client . "_"]['id'] = $client->getId();
                $invoicesToShow[$client . "_"]['month'] = $invoice->getBeginAt()->format("m");
                $invoicesToShow[$client . "_"]['name'] = $client->getName();
                $invoicesToShow[$client . "_"]['city'] = $invoice->getClient()->getCity();
                $invoicesToShow[$client . "_"]['userId'] = $invoice->getUser()->getId();

                if (isset($invoicesToShow[$client . "_"]['sum'])) {
                    $invoicesToShow[$client . "_"]['sum'] += (float) round($invoice->getRemuneration());
                } else {
                    $invoicesToShow[$client . "_"]['sum'] = (float) $invoice->getRemuneration();
                }


            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($invoice->getEndAt()->format("d") - $invoice->getBeginAt()->format("d")) + 1; // 5
                $client = $invoice->getInvoiceClient();

                for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $invoice;

                    $invoicesToShow[$client . "_"]['id'] = $client->getId();
                    $invoicesToShow[$client . "_"]['month'] = $newMission->getBeginAt()->format("m");
                    $invoicesToShow[$client . "_"]['name'] = $client->getName();
                    $invoicesToShow[$client . "_"]['city'] = $newMission->getClient()->getCity();
                    $invoicesToShow[$client . "_"]['userId'] = $invoice->getUser()->getId();

                    if (isset($invoicesToShow[$client . "_"]['sum'])) {
                        $invoicesToShow[$client . "_"]['sum'] += (float) round($invoice->getRemuneration());
                    } else {
                        $invoicesToShow[$client . "_"]['sum'] = (float) round($invoice->getRemuneration());
                    }

                }

            }

            $totalAmount = NULL;

            foreach ($invoicesToShow as $invoiceToShow) {

                $totalAmount += $invoiceToShow["sum"];


            }

        }

        return $this->render('profile/generate_invoice_type.html.twig', [
            'invoices' => $invoicesToShow,
            'totalAmount' => $totalAmount,
            'monthIndex' => $month,
            'year' => $year,
            'ca' => $data[1],
            'hasExpiredFile' => $this->controlFilesUpadated()
        ]);
    }


    #[Route('/profile/contracts/admin/{month}/{year}', name: 'app_contracts_admin')]
    public function contracts_admin(
        Request $request,
        EntityManagerInterface $doctrine,
        int $month,
        int $year
    ): Response {

        $data = $this->getValues($doctrine);
        $invoices = $doctrine->getRepository(Mission::class)->findMonthlyInvoicesToGenerate($year, $month);


        $invoicesToShow = NULL;
        foreach ($invoices as $invoice) {

            $client = $invoice->getUser()->getId();

            if ($invoice->getBeginAt() == $invoice->getEndAt()) {
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['id'] = $client;
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['month'] = $invoice->getBeginAt()->format("m");
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['name'] = $invoice->getUser()->getLastName() . ' ' . $invoice->getUser()->getFirstName();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['teacherPaid'] = $invoice->isTeacherPaid();

                if (isset($invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['sum'])) {
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['sum'] += (float) round($invoice->getHours() * $invoice->getHourlyRate());
                } else {
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['sum'] = (float) $invoice->getHours() * $invoice->getHourlyRate();
                }


            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($invoice->getEndAt()->format("d") - $invoice->getBeginAt()->format("d")) + 1; // 5

                for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $invoice;
                    $dateTime = new \DateTime;

                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['id'] = $client;
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['month'] = $newMission->getBeginAt()->format("m");
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['name'] = $invoice->getUser()->getLastName() . ' ' . $invoice->getUser()->getFirstName();
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['teacherPaid'] = $newMission->isTeacherPaid();

                    if (isset($invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['sum'])) {
                        $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['sum'] += (float) round($invoice->getHours() * $invoice->getHourlyRate());
                    } else {
                        $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['sum'] = (float) round($invoice->getHours() * $invoice->getHourlyRate());
                    }

                }

            }

            $totalAmount = NULL;

            foreach ($invoicesToShow as $invoiceToShow) {
                $totalAmount += $invoiceToShow["sum"];
            }

        }

        return $this->render('profile/contracts_admin.html.twig', [
            'invoices' => $invoicesToShow,
            'totalAmount' => $totalAmount,
            'year' => $year,
            'ca' => $data[1],
            'monthIndex' => $month,
            'hasExpiredFile' => $this->controlFilesUpadated()
        ]);
    }

    // route permettant d'afficher tous les contrats de prestations de formation
    // pour démarrer les années avec nos clients
    #[Route('/profile/contracts/prestations/b2b', name: 'app_contracts_prestations_client')]
    public function contracts_prestations_b2b(
        Request $request,
        EntityManagerInterface $doctrine
    ): Response {

        $data = $this->getValues($doctrine);
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
        $clients = $doctrine->getRepository(Mission::class)->findDistinctClientsForCurrentYear($year);

        return $this->render('profile/contracts_prestations_b2b.html.twig', [
            'clients' => $clients,
            'year' => $data[0],
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1]
        ]);

    }

    #[Route('/profile/skills', name: 'app_profile_skills')]
    public function skills(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
    ): Response {

        $data = $this->getValues($doctrine);

        $user = $this->getUser();
        $courses = $doctrine->getRepository(Courses::class)->findDistinctCoursesFromMissions($user->getId());

        $skills = $paginator->paginate(
            $courses, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('profile/skills.html.twig', [
            'skills' => $skills,
            'year' => $data[0],
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1]
        ]);
    }

    #[Route('/profile/infos', name: 'app_profile_infos')]
    public function address(
        Request $request,
        EntityManagerInterface $em,
        Filesystem $filesystem,
        User $user,
        EntityManagerInterface $doctrine,
        MailService $mailService
    ): Response {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $modifiedFields = [];

        $data = $this->getValues($doctrine);

        if ($form->isSubmitted() && $form->isValid()) {

            $attachments = [];
            $currentDate = (new \DateTime())->format('Y-m-d'); // Récupère la date actuelle pour le nommage des fichiers

            // Gestion du KBIS
            $kbisFile = $form->get('kbis')->getData();

            if (!is_null($kbisFile)) {
                // Supprimer l'ancien fichier s'il existe
                if ($user->getKbis()) {
                    $filesystem->remove($this->getParameter('uploads_directory') . '/kbis/' . $user->getKbis());
                }

                // Renommer et sauvegarder le fichier
                $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_' . $currentDate . '.' . $kbisFile->guessExtension();
                $kbisFile->move($this->getParameter('uploads_directory') . '/kbis', $newFilename);

                // Mise à jour du fichier et de la date
                $user->setKbis($newFilename);
                $user->setKbisUpdatedAt(new \DateTime());
                $modifiedFields[] = 'KBIS';
                $attachments[] = $this->getParameter('uploads_directory') . '/kbis/' . $newFilename;

            }

            // Gestion de l'attestation de vigilance
            $attestationFile = $form->get('attestationVigilance')->getData();
            if (!is_null($attestationFile)) {
                // Supprimer l'ancien fichier s'il existe
                if ($user->getAttestationVigilance()) {
                    $filesystem->remove($this->getParameter('uploads_directory') . '/vigilance/' . $user->getAttestationVigilance());
                }

                // Renommer et sauvegarder le fichier
                $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_' . $currentDate . '.' . $attestationFile->guessExtension();
                $attestationFile->move($this->getParameter('uploads_directory') . '/vigilance', $newFilename);

                // Mise à jour du fichier et de la date
                $user->setAttestationVigilance($newFilename);
                $user->setAttestationVigilanceUpdatedAt(new \DateTime());
                $modifiedFields[] = 'Attestation de vigilance';
                $attachments[] = $this->getParameter(name: 'uploads_directory') . '/vigilance/' . $newFilename;
            }

            // Gestion du casier judiciaire (criminalRecord)
            $criminalRecordFile = $form->get('criminalRecord')->getData();
            if (!is_null($criminalRecordFile)) {
                // Supprimer l'ancien fichier s'il existe
                if ($user->getCriminalRecord()) {
                    $filesystem->remove($this->getParameter('uploads_directory') . '/criminalRecords/' . $user->getCriminalRecord());
                }

                // Renommer et sauvegarder le fichier
                $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_' . $currentDate . '.' . $criminalRecordFile->guessExtension();
                $criminalRecordFile->move($this->getParameter('uploads_directory') . '/criminalRecords', $newFilename);

                // Mise à jour du fichier et de la date
                $user->setCriminalRecord($newFilename);
                $user->setCriminalRecordUpdatedAt(new \DateTime());
                $modifiedFields[] = 'Casier judiciaire';
                $attachments[] = $this->getParameter(name: 'uploads_directory') . '/criminalRecords/' . $newFilename;

            }

            // Gestion des diplômes (diplomas)
            $diplomasFile = $form->get('diplomas')->getData();
            if (!is_null($diplomasFile)) {
                // Supprimer l'ancien fichier s'il existe
                if ($user->getDiplomas()) {
                    $filesystem->remove($this->getParameter('uploads_directory') . '/diplomas/' . $user->getDiplomas());
                }

                // Renommer et sauvegarder le fichier
                $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_' . $currentDate . '.' . $diplomasFile->guessExtension();
                $diplomasFile->move($this->getParameter('uploads_directory') . '/diplomas', $newFilename);

                // Mise à jour du fichier et de la date
                $user->setDiplomas($newFilename);
                $user->setDiplomasUpdatedAt(new \DateTime());
                $modifiedFields[] = 'Diplômes';
                $attachments[] = $this->getParameter(name: 'uploads_directory') . '/diplomas/' . $newFilename;

            }

            // Gestion du CV
            $cvFile = $form->get('cv')->getData();
            if (!is_null($cvFile)) {
                // Supprimer l'ancien fichier s'il existe
                if ($user->getCV()) {
                    $filesystem->remove($this->getParameter('uploads_directory') . '/cv/' . $user->getCV());
                }

                // Renommer et sauvegarder le fichier
                $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_' . $currentDate . '.' . $cvFile->guessExtension();
                $cvFile->move($this->getParameter('uploads_directory') . '/cv', $newFilename);

                // Mise à jour du fichier et de la date
                $user->setCV($newFilename);
                $user->setCvUpdatedAt(new \DateTime());
                $modifiedFields[] = 'CV';
                $attachments[] = $this->getParameter(name: 'uploads_directory') . '/cv/' . $newFilename;

            }

            $attestationCompetenceFile = $form->get('attestationCompetence')->getData();
            if (!is_null($attestationCompetenceFile)) {
                // Supprimer l'ancien fichier s'il existe
                if ($user->getAttestationCompetence()) {
                    $filesystem->remove($this->getParameter('uploads_directory') . '/competence/' . $user->getAttestationCompetence());
                }

                // Renommer et sauvegarder le fichier
                $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_' . $currentDate . '.' . $attestationCompetenceFile->guessExtension();
                $attestationCompetenceFile->move($this->getParameter('uploads_directory') . '/competence', $newFilename);

                // Mise à jour du fichier et de la date
                $user->setAttestationCompetence($newFilename);
                $user->setAttestationCompetenceUpdatedAt(new \DateTime());
                $modifiedFields[] = 'Attestation de compétence';
                $attachments[] = $this->getParameter('uploads_directory') . '/competence/' . $newFilename;
            }

            // Enregistrement en base de données
            $em->flush();

            // Si des champs ont été modifiés, on envoie un email
            if (!empty($modifiedFields)) {
                $mailData = [
                    'modifiedFields' => $modifiedFields,
                    'user' => $user
                ];

                // Envoyer l'email avec pièces jointes si des fichiers ont été uploadés
                $mailService->SendMailWithAttachments($mailData, $this->params->get('app.mail_address'), 'Modifications des informations formateurs', 'emails/profile_update.html.twig', $attachments);
            }

            $this->addFlash('success', 'Votre modifications ont bien été apportées !');

            return $this->redirectToRoute('app_profile_infos');
        }

        return $this->render('profile/address.html.twig', [
            'year' => $data[0],
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1],
            'form' => $form->createView(),
        ]);
    }


    #[Route('/profile/opportunities', name: 'app_jobs_for_you')]
    public function opportunities(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator
    ): Response {

        $data = $this->getValues($doctrine);
        $user = $this->getUser();
        $themesId = $doctrine->getRepository(Mission::class)->findDistinctThemesFromUserMissions($user->getId());

        $listThemesId = [];

        foreach ($themesId as $theme) {
            $listThemesId[] = $theme["id"];
        }

        foreach ($listThemesId as $key => $val) {
            $listThemesId[$key] = "'" . $val . "'";
        }

        $in = implode(",", $listThemesId);


        $jobs = $doctrine->getRepository(Jobs::class)->findJobsByThemeIds($in);

        $staffApplication = $doctrine->getRepository(StaffApplication::class)->findBy(["user" => $this->getUser()->getUserIdentifier()]);
        $listJobsApplied = [];
        foreach ($staffApplication as $val) {
            $listJobsApplied[] = $val->getJob()->getId();
        }

        return $this->render('profile/opportunities.html.twig', [
            'jobs' => $jobs,
            'staffApplication' => $staffApplication,
            'listJobsApplied' => $listJobsApplied,
            'year' => $data[0],
            'hasExpiredFile' => $this->controlFilesUpadated(),
            'ca' => $data[1]
        ]);
    }

    #[Route('/profile/invoices/paid/{month}/{year}', name: 'app_invoice_paid')]
    public function updateInvoicePaid(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        string $month,
        string $year
    ) {

        if ($request->isMethod('POST')) {

            $clientId = $request->get('clientId');
            $month = $request->get('month');
            $year = $request->get('year');
            $paid = $request->get('paid');
            // $dateTime = new \DateTime("now");
            // $year = $dateTime->format("Y");

            // mettre à jour le paiment client de toutes les missions
            $doctrine->getRepository(Mission::class)->updateClientPaidForMissions($year, $month, $clientId, $paid);

            return new Response('Facture mise à jour', 200);

        }

        return new Response('Une erreur est survenue', 500);

    }

    #[Route('/profile/invoices/sent/{month}/{year}', name: 'app_invoice_sent')]
    public function updateInvoiceSent(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        int $month,
        int $year
    ) {

        if ($request->isMethod('POST')) {

            $clientId = $request->get('clientId');
            $month = $request->get('month');
            $year = $request->get('year');
            $sent = $request->get('sent');
            // $dateTime = new \DateTime("now");
            // $year = $dateTime->format("Y");

            // mettre à jour le paiment client de toutes les missions
            $doctrine->getRepository(Mission::class)->updateInvoiceSentForMissions($year, $month, $clientId, $sent);

            return new Response('Facture envoyée', 200);

        }

        return new Response('Une erreur est survenue', 500);

    }

    #[Route('/profile/invoices/teacher/paid/{month}', name: 'app_invoice_teacher_paid')]
    public function updateInvoiceTeacherPaid(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        int $month
    ) {

        if ($request->isMethod('POST')) {

            $teacherId = $request->get('teacherId');
            $month = $request->get('month');
            $paid = $request->get('paid');
            $dateTime = new \DateTime("now");
            $year = $dateTime->format("Y");

            // mettre à jour le paiment client de toutes les missions
            $doctrine->getRepository(Mission::class)->updateTeacherPaidForMissions($year, $month, $teacherId, $paid);

            return new Response('Facture mise à jour', 200);

        }

        return new Response('Une erreur est survenue', 500);

    }


    #[Route('/profile/invoices/{year}/{month}', name: 'app_invoices')]
    public function invoices(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        int $month,
        int $year
    ): Response {

        $data = $this->getValues($doctrine);
        // $dateTime = new \DateTime("now");
        // $year = $dateTime->format("Y");

        $invoices = $doctrine->getRepository(Mission::class)->findMonthlyInvoicesToGenerate($year, $month);

        $invoicesToShow = NULL;
        foreach ($invoices as $invoice) {

            $client = $invoice->getClient()->getId();

            if ($invoice->getBeginAt() == $invoice->getEndAt()) {
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['id'] = $client;
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['month'] = $invoice->getBeginAt()->format("m");
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['name'] = $invoice->getClient()->getName();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['clientPaid'] = $invoice->isClientPaid();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['invoiceSent'] = $invoice->isInvoiceSent();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['orderNumber'] = $invoice->getOrderNumber();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['city'] = $invoice->getClient()->getCity();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['personInCharge'] = $invoice->getClient()->getPersonInCharge();
                $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['titleMission'] = $invoice->getTitleMission();

                if (isset($invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['sum'])) {
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['sum'] += (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice(), 2);
                } else {
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['sum'] = (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice(), 2);
                }


            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($invoice->getEndAt()->format("d") - $invoice->getBeginAt()->format("d")) + 1; // 5

                for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $invoice;
                    $dateTime = new \DateTime;

                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['id'] = $client;
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['month'] = $newMission->getBeginAt()->format("m");
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['name'] = $newMission->getClient()->getName();
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['clientPaid'] = $newMission->isClientPaid();
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['invoiceSent'] = $newMission->isInvoiceSent();
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['orderNumber'] = $newMission->getOrderNumber();
                    $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['city'] = $newMission->getClient()->getCity();
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['personInCharge'] = $invoice->getClient()->getPersonInCharge();
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['personInCharge'] = $invoice->getClient()->getPersonInCharge();
                    $invoicesToShow[$client . "_" . $invoice->getOrderNumber()]['titleMission'] = $invoice->getTitleMission();

                    if (isset($invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['sum'])) {
                        $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['sum'] += (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice(), 2);
                    } else {
                        $invoicesToShow[$client . "_" . $newMission->getOrderNumber()]['sum'] = (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice(), 2);
                    }

                }

            }

            $totalAmount = NULL;

            foreach ($invoicesToShow as $invoiceToShow) {

                $totalAmount += $invoiceToShow["sum"];

            }

        }


        return $this->render('profile/invoices.html.twig', [
            'invoices' => $invoicesToShow,
            'totalAmount' => $totalAmount,
            'monthIndex' => $month,
            'year' => $year,
            'ca' => $data[1],
            'hasExpiredFile' => $this->controlFilesUpadated(),
        ]);

    }

    /**
     * Méthode permettant de générer une facture pour le mois en cours pour le formateur
     * // au cous il ne sait pas le faire !
     */
    #[Route('/profile/generate/invoices/type/{year}/{month}/{clientId}', name: 'app_generate_invoice_type_pdf')]
    public function generate_invoice_type_pdf(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        int $month,
        int $year,
        int $clientId
    ): Response {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            $data = $this->getValues($doctrine);

            $dateTime = new \DateTime("now");
            $missions = $doctrine->getRepository(Mission::class)->findAllMissionToGenerateInvoiceForTeacher($year, $month, $clientId, $this->getUser()->getId());
            $client = $doctrine->getRepository(Clients::class)->find($clientId);
            $invoice = NULL;
            // va falloir regrouper les missions par formateurs et par cours
            // TODO
            $course = NULL;
            $user = NULL;
            $missionsForInvoice = NULL;
            $totalAmount = NULL;

            foreach ($missions as $mission) {

                $course = $mission->getCourse();
                $user = $mission->getUser()->getId();

                if ($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsForInvoice[$user][$mission->getCourse()->getId() . $mission->getStudent()->getId()][] = $mission;
                    $totalAmount += round($mission->getRemuneration(), 2);

                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                    for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTime = new \DateTime;

                        if ($i == 0) {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d")
                            );
                        } else {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i
                            );
                        }

                        $newMission->setBeginAt($dateTime);
                        $totalAmount += round($newMission->getRemuneration(), 2);
                        $missionsForInvoice[$user][$newMission->getCourse()->getId() . $newMission->getStudent()->getId()][] = $newMission;
                    }

                }

            }

            // pour récupérer l'année et le mois actuel pour les dates de factures et d'échéances
            // $year = $dateTime->format("Y");
            // $month = $dateTime->format("m");

            $invoiceDate = new \DateTime($year . '-' . $month . '-01');
            $invoiceDate = $invoiceDate->modify('first day of next month');
            $invoiceDate = $invoiceDate->format('d-m-Y');

            $dateEcheance = new \DateTime($year . '-' . $month . '-01');
            $dateEcheance = $dateEcheance->modify('first day of next month');
            $dateEcheance = $dateEcheance->modify('first day of next month');

            $invoiceDateEcheance = $dateEcheance->format('d-m-Y');

            $date = new \DateTime($year . '-' . $month . '-01');
            $invoiceNumber = "F" . $date->format("Ym") . '_' . $this->getUser()->getId() . '_' . $clientId;

            $data = [
                'missions' => $missionsForInvoice,
                'teacher' => $this->getUser(),
                'invoiceNumber' => $invoiceNumber,
                'invoiceDate' => $invoiceDate,
                'invoiceDateEcheance' => $invoiceDateEcheance,
                'totalAmount' => round($totalAmount, 2),
                'client' => $client,
                'year' => $year,
                'monthIndex' => $month,
                'clientInvoice' => $missions[0]->getInvoiceClient()->getId()
            ];
            $html = $this->renderView('profile/invoices-template-type-for-teacher.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response(
                $dompdf->stream($this->params->get('nom_entreprise') . " - " . $invoiceNumber, ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

    }

    #[Route('/profile/invoices/{year}/{month}/{clientId}/{orderNumber}', name: 'app_generate_invoice')]
    public function generateInvoice(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        InvoicesRepository $invoicesRepository,
        ClientsRepository $clientsRepository,
        EntityManagerInterface $entityManager,
        int $year,
        int $month,
        int $clientId,
        $orderNumber = null
    ): Response {

        // TO CORRECT NAME OF PDF
        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            $data = $this->getValues($doctrine);

            // $dateTime = new \DateTime("now");
            $dateTime = new \DateTime(sprintf('%04d-%02d-01', $year, $month));
            $missions = $doctrine->getRepository(Mission::class)->findMissionForCustomer($year, $month, $clientId, $orderNumber);
            $client = $doctrine->getRepository(Clients::class)->find($clientId);
            $invoice = NULL;
            // va falloir regrouper les missions par formateurs et par cours
            // TODO
            $course = NULL;
            $user = NULL;
            $missionsForInvoice = NULL;
            $totalAmount = NULL;
            $orderNumber = NULL;
            $contractsNumber = [];
            $codeModule = NULL;

            foreach ($missions as $mission) {

                $course = $mission->getCourse();
                $user = $mission->getUser()->getId();

                if (is_null($orderNumber) && !is_null($mission->getOrderNumber())) {
                    $orderNumber = $mission->getOrderNumber();
                }

                if (!is_null($mission->getContractNumber()) && !in_array($mission->getContractNumber(), $contractsNumber) ) {
                    array_push($contractsNumber,$mission->getContractNumber());
                }

                if (is_null($codeModule) && !is_null($mission->getCodeModule())) {
                    $codeModule = $mission->getCodeModule();
                }

                if ($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsForInvoice[$user][$mission->getCourse()->getId() . $mission->getStudent()->getId()][] = $mission;
                    $totalAmount += round($mission->getHours() * $mission->getStudent()->getHourlyPrice(), 2);

                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                    for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTimeForIteration = new \DateTime;

                        if ($i == 0) {
                            $dateTimeForIteration->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d")
                            );
                        } else {
                            $dateTimeForIteration->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i
                            );
                        }

                        $newMission->setBeginAt($dateTimeForIteration);
                        $totalAmount += round($newMission->getHours() * $newMission->getStudent()->getHourlyPrice(), 2);
                        $missionsForInvoice[$user][$newMission->getCourse()->getId() . $newMission->getStudent()->getId()][] = $newMission;
                    }

                }

            }

            // pour récupérer l'année et le mois actuel pour les dates de factures et d'échéances
            $year = $dateTime->format("Y");

            $month = $dateTime->format("m");

            $invoiceDate = new \DateTime($year . '-' . $month . '-01');
            $invoiceDate = $invoiceDate->modify('first day of next month');

            $dateEcheance = new \DateTime($year . '-' . $month . '-01');
            $dateEcheance = $dateEcheance->modify('first day of next month');
            $dateEcheance = $dateEcheance->modify('first day of next month');

            $invoiceDate = $invoiceDate->format('d-m-Y');
            $invoiceDateEcheance = $dateEcheance->format('d-m-Y');

            $date = new \DateTime($year . '-' . $month . '-01');
            // $date = $date->modify( 'first day of next month' );
            //$invoiceNumber = "F" . $date->format("Ym") . '_' . $clientId;

            // Vérifie si une facture existe déjà
            $existingInvoice = $invoicesRepository->findInvoiceByMonthAndYear($clientId, $year, $month);

            if ($existingInvoice) {
                // Si elle existe, on récupère le numéro de la facture
                $invoiceNumber = $existingInvoice->getId();
            } else {
                // Si elle n'existe pas, on crée une nouvelle facture
                $client = $clientsRepository->find($clientId);
                if (!$client) {
                    throw $this->createNotFoundException('Client not found');
                }

                $newInvoice = new Invoices();
                $newInvoice->setClient($client);
                $newInvoice->setYear($year);
                $newInvoice->setMonth($month);

                $entityManager->persist($newInvoice);
                $entityManager->flush();

                // Récupère l'ID de la nouvelle facture
                $invoiceNumber = $newInvoice->getId();
            }

            $data = [
                'missions' => $missionsForInvoice,
                'teacher' => $user,
                'invoiceNumber' => "N°" . $invoiceNumber,
                'invoiceDate' => $invoiceDate,
                'invoiceDateEcheance' => $invoiceDateEcheance,
                'totalAmount' => round($totalAmount, 2),
                'client' => $client,
                'orderNumber' => $orderNumber,
                'contractsNumber' => $contractsNumber,
                'codeModule' => $codeModule
            ];

            $html = $this->renderView('profile/invoices-template.html.twig', $data);

            // Génération du PDF
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            // Envoi du PDF en réponse HTTP
            $responseContent = $dompdf->output();

            return new Response(
                $responseContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="Facture-' . $invoiceNumber . '.pdf"',
                ]
            );

        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/profile/invoices/{year}/{month}/{clientId}/{orderNumber}', name: 'app_generate_devis')]
    public function generateEstimate(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        EstimateRepository $estimateRepository,
        ClientsRepository $clientsRepository,
        EntityManagerInterface $entityManager,
        int $year,
        int $month,
        int $clientId,
        $orderNumber = null
    ): Response {

        // TO CORRECT NAME OF PDF
        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            $data = $this->getValues($doctrine);

            // $dateTime = new \DateTime("now");
            $dateTime = new \DateTime(sprintf('%04d-%02d-01', $year, $month));
            $missions = $doctrine->getRepository(Mission::class)->findMissionForCustomer($year, $month, $clientId, $orderNumber);
            $client = $doctrine->getRepository(Clients::class)->find($clientId);
            $invoice = NULL;
            // va falloir regrouper les missions par formateurs et par cours
            // TODO
            $course = NULL;
            $user = NULL;
            $missionsForInvoice = NULL;
            $totalAmount = NULL;
            $orderNumber = NULL;

            foreach ($missions as $mission) {

                $course = $mission->getCourse();
                $user = $mission->getUser()->getId();

                if (is_null($orderNumber) && !is_null($mission->getOrderNumber())) {
                    $orderNumber = $mission->getOrderNumber();
                }

                if ($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsForInvoice[$user][$mission->getCourse()->getId() . $mission->getStudent()->getId()][] = $mission;
                    $totalAmount += round($mission->getHours() * $mission->getStudent()->getHourlyPrice(), 2);

                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                    for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTimeForIteration = new \DateTime;

                        if ($i == 0) {
                            $dateTimeForIteration->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d")
                            );
                        } else {
                            $dateTimeForIteration->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i
                            );
                        }

                        $newMission->setBeginAt($dateTimeForIteration);
                        $totalAmount += round($newMission->getHours() * $newMission->getStudent()->getHourlyPrice(), 2);
                        $missionsForInvoice[$user][$newMission->getCourse()->getId() . $newMission->getStudent()->getId()][] = $newMission;
                    }

                }

            }

            $devisDate = new \DateTime();
            $devisDate = $devisDate->format('d-m-Y');

            $dateEcheance = new \DateTime($year . '-' . $month . '-01');
            $dateEcheance = $dateEcheance->modify('first day of next month');
            $dateEcheance = $dateEcheance->modify('first day of next month');

            $devisDateEcheance = $dateEcheance->format('d-m-Y');

            // $date = $date->modify( 'first day of next month' );
            //$devisNumber = "F" . $date->format("Ym") . '_' . $clientId;

            // Vérifie si une facture existe déjà
            $existingDevis = $estimateRepository->findEstimateByMonthAndYear($clientId, $year, $month);

            if ($existingDevis) {
                // Si elle existe, on récupère le numéro de la facture
                $devisNumber = $existingDevis->getId();
            } else {
                // Si elle n'existe pas, on crée une nouvelle facture
                $client = $clientsRepository->find($clientId);
                if (!$client) {
                    throw $this->createNotFoundException('Client not found');
                }

                $newInvoice = new Estimate();
                $newInvoice->setClient($client);
                $newInvoice->setYear($year);
                $newInvoice->setMonth($month);

                $entityManager->persist($newInvoice);
                $entityManager->flush();

                // Récupère l'ID de la nouvelle facture
                $devisNumber = $newInvoice->getId();
            }

            $data = [
                'missions' => $missionsForInvoice,
                'teacher' => $user,
                'devisNumber' => "N°" . $devisNumber,
                'devisDate' => $devisDate,
                'devisDateEcheance' => $devisDateEcheance,
                'totalAmount' => round($totalAmount, 2),
                'client' => $client,
                'orderNumber' => $orderNumber
            ];
            $html = $this->renderView('profile/devis-template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response(
                $dompdf->stream($this->params->get('nom_entreprise') . " - Facture n°" . $devisNumber . " - " . $client->getName(), ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/profile/invoices/teacher/{year}/{month}/{clientId}/{userId}', name: 'app_generate_invoice_teacher')]
    public function generateInvoiceForOneTeacher(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        int $year,
        int $month,
        int $clientId,
        int $userId
    ): Response {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            $data = $this->getValues($doctrine);

            $dateTime = new \DateTime("now");
            // $year = $dateTime->format("Y");
            $missions = $doctrine->getRepository(Mission::class)->findMissionForCustomerAndOneTeacher($year, $month, $clientId, $userId);
            $client = $doctrine->getRepository(Clients::class)->find($clientId);
            $invoice = NULL;
            // va falloir regrouper les missions par formateurs et par cours
            // TODO
            $course = NULL;
            $user = NULL;
            $missionsForInvoice = NULL;
            $totalAmount = NULL;
            $orderNumber = NULL;

            foreach ($missions as $mission) {

                $course = $mission->getCourse();
                $user = $mission->getUser()->getId();

                if (is_null($orderNumber) && !is_null($mission->getOrderNumber())) {
                    $orderNumber = $mission->getOrderNumber();
                }

                if ($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsForInvoice[$user][$mission->getCourse()->getId() . $mission->getStudent()->getId()][] = $mission;
                    $totalAmount += round($mission->getHours() * $mission->getStudent()->getHourlyPrice(), 0);

                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                    for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTime = new \DateTime;

                        if ($i == 0) {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d")
                            );
                        } else {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i
                            );
                        }

                        $newMission->setBeginAt($dateTime);
                        $totalAmount += round($newMission->getHours() * $newMission->getStudent()->getHourlyPrice(), 0);
                        $missionsForInvoice[$user][$newMission->getCourse()->getId() . $newMission->getStudent()->getId()][] = $newMission;
                    }

                }

            }

            // pour récupérer l'année et le mois actuel pour les dates de factures et d'échéances
            $year = $dateTime->format("Y");
            // $month = $dateTime->format("m");

            $invoiceDate = new \DateTime($year . '-' . $month . '-01');
            $invoiceDate = $invoiceDate->modify('first day of next month');

            $dateEcheance = new \DateTime($year . '-' . $month . '-01');
            $dateEcheance = $dateEcheance->modify('first day of next month')->modify('first day of next month');

            $invoiceDate = $invoiceDate->format('d-m-Y');
            $invoiceDateEcheance = $dateEcheance->format('d-m-Y');

            $date = new \DateTime($year . '-' . $month . '-01');
            $invoiceNumber = "F" . $date->format("Ym") . '_' . $mission->getClient()->getId() . '_' . $mission->getUser()->getId();

            $data = [
                'missions' => $missionsForInvoice,
                'teacher' => $user,
                'invoiceNumber' => $invoiceNumber,
                'invoiceDate' => $invoiceDate,
                'invoiceDateEcheance' => $invoiceDateEcheance,
                'totalAmount' => round($totalAmount),
                'client' => $client,
                'orderNumber' => $orderNumber
            ];
            $html = $this->renderView('profile/invoices-template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response(
                $dompdf->stream($this->params->get('nom_entreprise') . " - " . $invoiceNumber, ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/profile/opportunities/{id}', name: 'app_profile_application')]
    public function accept_opportunity(
        Request $request,
        PersistenceManagerRegistry $doctrine,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {

        $staffApplication = new StaffApplication();
        $job = $doctrine->getRepository(Jobs::class)->findBy(["id" => $id])[0];
        $staffApplication->setDate(new \DateTime);
        $staffApplication->setUser($this->getUser());
        $staffApplication->setJob($job);

        $entityManager->persist($staffApplication);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez bien été postulé pour cette mission !');

        return $this->redirectToRoute('app_jobs_for_you');

    }


    public function getValues(EntityManagerInterface $doctrine)
    {

        $userId = $this->getUser()->getId();
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
        $missions = $doctrine->getRepository(Mission::class)->findCaForCurrentYear($year, $userId);
        $ca = null;

        foreach ($missions as $mission) {

            // si il s'agit d'une mission d'une journée
            // j'ajoute à la liste des missions
            if ($mission->getBeginAt() == $mission->getEndAt()) {
                $missionsToDisplay[] = $mission;
                $ca += $mission->getRemuneration();
            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $mission;
                    $dateTime = new \DateTime;

                    if ($i == 0) {
                        $dateTime->setDate(
                            $mission->getBeginAt()->format("Y"),
                            $mission->getBeginAt()->format("m"),
                            $mission->getBeginAt()->format("d")
                        );
                    } else {
                        $dateTime->setDate(
                            $mission->getBeginAt()->format("Y"),
                            $mission->getBeginAt()->format("m"),
                            $mission->getBeginAt()->format("d") + $i
                        );
                    }

                    $newMission->setBeginAt($dateTime);
                    $ca += $newMission->getRemuneration();
                }

            }
        }

        return [$year, round($ca, 2)];

    }

    #[Route('/profile/clients/payment', name: 'app_clients_payments')]
    public function clientsPayment(
        Request $request,
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator,
        int $month
    ): Response {


        return $this->render('profile/payment-clients.html.twig', [

        ]);

    }

    public function controlFilesUpadated()
    {
        $user = $this->getUser(); // Supposons que vous avez l'utilisateur

        // Date actuelle
        $currentDate = new \DateTime();
        $sixMonthsAgo = $currentDate->modify('-6 months');
        $hasExpiredFile = false;

        if (
            is_null($user->getKbisUpdatedAt())
            || is_null($user->getCvUpdatedAt())
            || is_null($user->getDiplomasUpdatedAt())
            || is_null($user->getCriminalRecordUpdatedAt())
            || is_null($user->getAttestationVigilanceUpdatedAt())
            || is_null($user->getAttestationCompetenceUpdatedAt())
        ) {
            return true;
        }

        // Vérifier si un des fichiers est plus vieux que 6 mois
        if ($user->getKbisUpdatedAt() && $user->getKbisUpdatedAt() < $sixMonthsAgo) {
            $hasExpiredFile = true;
        } elseif ($user->getCvUpdatedAt() && $user->getCvUpdatedAt() < $sixMonthsAgo) {
            $hasExpiredFile = true;
        } elseif ($user->getDiplomasUpdatedAt() && $user->getDiplomasUpdatedAt() < $sixMonthsAgo) {
            $hasExpiredFile = true;
        } elseif ($user->getCriminalRecordUpdatedAt() && $user->getCriminalRecordUpdatedAt() < $sixMonthsAgo) {
            $hasExpiredFile = true;
        } elseif ($user->getAttestationVigilanceUpdatedAt() && $user->getAttestationVigilanceUpdatedAt() < $sixMonthsAgo) {
            $hasExpiredFile = true;
        } elseif ($user->getAttestationCompetenceUpdatedAt() && $user->getAttestationCompetenceUpdatedAt() < $sixMonthsAgo) {
            $hasExpiredFile = true;
        }

        return $hasExpiredFile;

    }

    #[Route('/responsable-details/{id}', name: 'responsable_details', methods: ['GET'])]
    public function responsableDetails(int $id, ClientsRepository $clientRepository): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return new JsonResponse(['error' => 'Responsable pédagogique introuvable'], 404);
        }

        return new JsonResponse([
            'name' => $client->getName(),
            'city' => $client->getCity(),
            'address' => $client->getAddress(),
            'postalCode' => $client->getPostalCode(),
            'personInCharge' => $client->getPersonInCharge(),
            'representative' => $client->getPersonInCharge(),
            'emailPersonInCharge' => $client->getEmailPersonInCharge(),
            'emailRepresentative' => $client->getEmailRepresentative(),
            'phonePersonInCharge' => $client->getPhonePersonInCharge(),
            'phoneRepresentative' => $client->getPhoneRepresentative(),
            'accountantEmail' => $client->getAccountantEmail(),
            'emailContactToAdd' => $client->getEmailContactToAdd(),
        ]);
    }

}
