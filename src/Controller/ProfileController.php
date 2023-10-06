<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Jobs;
use App\Entity\Mission;
use App\Entity\StaffApplication;
use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 
use Dompdf\Dompdf;

class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $doctrine): Response
    {

        $data = $this->getValues($doctrine);

        return $this->render('profile/index.html.twig', [
            'year' => $data[0],
            'ca' => $data[1]
        ]);
    }

    #[Route('/profile/password', name: 'app_profile_password')]
    public function edit(Request $request, 
    UserPasswordHasherInterface $passwordEncoder, 
    EntityManagerInterface $doctrine,
    EntityManagerInterface $entityManager): Response
    {

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
            'ca' => $data[1]
        ]);

    }

    #[Route('/profile/contracts', name: 'app_profile_contracts')]
    public function contracts(Request $request, EntityManagerInterface $doctrine): Response
    {

        $data = $this->getValues($doctrine);

        $userId = $this->getUser()->getId();
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
        $missions = $doctrine->getRepository(Mission::class)->findAnnualMissions($userId, $year);
        // dd($missions);
        return $this->render('profile/contracts.html.twig', [
            'contracts' => $missions,
            'userId' => $userId,
            'year' => $data[0],
            'ca' => $data[1]
        ]);
    }

    #[Route('/profile/contracts/admin/{month}', name: 'app_contracts_admin')]
    public function contracts_admin(Request $request,
        EntityManagerInterface $doctrine, int $month): Response
    {

        $data = $this->getValues($doctrine);
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
    
        $invoices = $doctrine->getRepository(Mission::class)->findMonthlyInvoicesToGenerate($year, $month);

        // dd($invoices);

        $invoicesToShow = NULL;
        foreach($invoices as $invoice) {

            $client = $invoice->getUser()->getId();

            if($invoice->getBeginAt() == $invoice->getEndAt()) {
                $invoicesToShow[$client]['id'] = $client;
                $invoicesToShow[$client]['month'] = $invoice->getBeginAt()->format("m");
                $invoicesToShow[$client]['name'] = $invoice->getUser()->getLastName() . ' ' . $invoice->getUser()->getFirstName();
                $invoicesToShow[$client]['teacherPaid'] = $invoice->isTeacherPaid();

                if(isset($invoicesToShow[$client]['sum'])) {
                    $invoicesToShow[$client]['sum'] += (float) round($invoice->getHours() * $invoice->getHourlyRate());
                } else {
                    $invoicesToShow[$client]['sum'] = (float) $invoice->getHours() * $invoice->getHourlyRate();
                }


            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($invoice->getEndAt()->format("d") - $invoice->getBeginAt()->format("d")) + 1; // 5
                
                for($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $invoice;
                    $dateTime = new \DateTime;

                    $invoicesToShow[$client]['id'] = $client;
                    $invoicesToShow[$client]['month'] = $newMission->getBeginAt()->format("m");
                    $invoicesToShow[$client]['name'] = $invoice->getUser()->getLastName() . ' ' . $invoice->getUser()->getFirstName();
                    $invoicesToShow[$client]['teacherPaid'] = $newMission->isTeacherPaid();
                    
                    if(isset($invoicesToShow[$client]['sum'])) {
                        $invoicesToShow[$client]['sum'] += (float) round($invoice->getHours() * $invoice->getHourlyRate());
                    } else {
                        $invoicesToShow[$client]['sum'] = (float) round($invoice->getHours() * $invoice->getHourlyRate());
                    }

                }

            }
            
            $totalAmount = NULL;

            foreach($invoicesToShow as $invoiceToShow) {
                $totalAmount +=  $invoiceToShow["sum"];
            }

        }
    
        // dd($missions);
        return $this->render('profile/contracts_admin.html.twig', [
            'invoices' => $invoicesToShow,
            'totalAmount' => $totalAmount,
            'year' => $data[0],
            'ca' => $data[1],
            'monthIndex' => $month,
        ]);
    }

    #[Route('/profile/skills', name: 'app_profile_skills')]
    public function skills(Request $request, 
        EntityManagerInterface $doctrine,
        PaginatorInterface $paginator): Response
    {

        $data = $this->getValues($doctrine);

        $user = $this->getUser();
        $user = $doctrine->getRepository(User::class)->findBy(["id" => $user->getUserIdentifier()])[0];

        $skills = $paginator->paginate(
            $user->getCourses(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('profile/skills.html.twig', [
            'skills' => $skills,
            'year' => $data[0],
            'ca' => $data[1]
        ]);
    }
    #[Route('/profile/infos', name: 'app_profile_infos')]
    public function address(Request $request, EntityManagerInterface $doctrine): Response
    {

        $data = $this->getValues($doctrine);
        return $this->render('profile/address.html.twig', [
            'year' => $data[0],
            'ca' => $data[1]        
        ]);
        
    }

    #[Route('/profile/opportunities', name: 'app_jobs_for_you')]
    public function opportunities(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator): Response
    {

        $data = $this->getValues($doctrine);
        $user = $this->getUser();
        $user = $doctrine->getRepository(User::class)->findBy(["id" => $user->getUserIdentifier()])[0];
        
        $listCoursesId = [];

        foreach($user->getCourses() as $course) {
            $listCoursesId[] = $course->getId();
        }

        foreach ($listCoursesId as $key => $val) {
            $listCoursesId[$key] = "'". $val . "'";
        }

        $in = implode("," , $listCoursesId);
        $jobs = $doctrine->getRepository(Jobs::class)->findJobsByCourses($in);

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
            'ca' => $data[1]
        ]);
    }

    #[Route('/profile/invoices/paid/{month}', name: 'app_invoice_paid')]
    public function updateInvoicePaid(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator, int $month)
    {

        if ($request->isMethod('POST')) {

            $clientId = $request->get('clientId');
            $month = $request->get('month');
            $paid = $request->get('paid');
            // dd($paid);
            $dateTime = new \DateTime("now");
            $year = $dateTime->format("Y");
            
            // dd($paid);
            // mettre à jour le paiment client de toutes les missions
            $doctrine->getRepository(Mission::class)->updateClientPaidForMissions($year, $month, $clientId, $paid);

            return new Response('Facture mise à jour', 200);

        }

        return new Response('Une erreur est survenue', 500);

    }

    #[Route('/profile/invoices/teacher/paid/{month}', name: 'app_invoice_teacher_paid')]
    public function updateInvoiceTeacherPaid(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator, int $month)
    {

        if ($request->isMethod('POST')) {

            $teacherId = $request->get('teacherId');
            $month = $request->get('month');
            $paid = $request->get('paid');
            $dateTime = new \DateTime("now");
            $year = $dateTime->format("Y");
            
            // dd($paid);
            // mettre à jour le paiment client de toutes les missions
            $doctrine->getRepository(Mission::class)->updateTeacherPaidForMissions($year, $month, $teacherId, $paid);

            return new Response('Facture mise à jour', 200);

        }

        return new Response('Une erreur est survenue', 500);

    }


    #[Route('/profile/invoices/{month}', name: 'app_invoices')]
    public function invoices(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator, int $month): Response
    {

        $data = $this->getValues($doctrine);
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
    
        $invoices = $doctrine->getRepository(Mission::class)->findMonthlyInvoicesToGenerate($year, $month);

        // dd($invoices);

        $invoicesToShow = NULL;
        foreach($invoices as $invoice) {

            $client = $invoice->getClient()->getId();

            if($invoice->getBeginAt() == $invoice->getEndAt()) {
                $invoicesToShow[$client]['id'] = $client;
                $invoicesToShow[$client]['month'] = $invoice->getBeginAt()->format("m");
                $invoicesToShow[$client]['name'] = $invoice->getClient()->getName();
                $invoicesToShow[$client]['clientPaid'] = $invoice->isClientPaid();
                $invoicesToShow[$client]['city'] = $invoice->getClient()->getCity();

                if(isset($invoicesToShow[$client]['sum'])) {
                    $invoicesToShow[$client]['sum'] += (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice());
                } else {
                    $invoicesToShow[$client]['sum'] = (float) $invoice->getHours() * $invoice->getStudent()->getHourlyPrice();
                }


            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($invoice->getEndAt()->format("d") - $invoice->getBeginAt()->format("d")) + 1; // 5
                
                for($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $invoice;
                    $dateTime = new \DateTime;

                    $invoicesToShow[$client]['id'] = $client;
                    $invoicesToShow[$client]['month'] = $newMission->getBeginAt()->format("m");
                    $invoicesToShow[$client]['name'] = $newMission->getClient()->getName();
                    $invoicesToShow[$client]['clientPaid'] = $newMission->isClientPaid();
                    $invoicesToShow[$client]['city'] = $newMission->getClient()->getCity();
                    if(isset($invoicesToShow[$client]['sum'])) {
                        $invoicesToShow[$client]['sum'] += (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice());
                    } else {
                        $invoicesToShow[$client]['sum'] = (float) round($invoice->getHours() * $invoice->getStudent()->getHourlyPrice());
                    }

                }

            }
            
            $totalAmount = NULL;

            foreach($invoicesToShow as $invoiceToShow) {

                $totalAmount +=  $invoiceToShow["sum"];


            }

        }

        // dd($invoicesToShow);
        // dd($invoices);
        // dd($missions);
        return $this->render('profile/invoices.html.twig', [
            'invoices' => $invoicesToShow,
            'totalAmount' => $totalAmount,
            'year' => $data[0],
            'monthIndex' => $month,
            'ca' => $data[1]
        ]);

    }

    #[Route('/profile/invoices/{year}/{month}/{clientId}', name: 'app_generate_invoice')]
    public function generateInvoice(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator, int $year, int $month, int $clientId): Response
    {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            $data = $this->getValues($doctrine);

            $dateTime = new \DateTime("now");
            $year = $dateTime->format("Y");
            $missions = $doctrine->getRepository(Mission::class)->findMissionForCustomer($year, $month, $clientId);
            $client = $doctrine->getRepository(Clients::class)->find($clientId);
            $invoice = NULL;
            // va falloir regrouper les missions par formateurs et par cours
            // TODO
            // dd($missions);
            $course = NULL;
            $user = NULL;
            $missionsForInvoice = NULL;
            $totalAmount = NULL;
            $orderNumber = NULL;

            // dd($missions);

            foreach($missions as $mission) {

                $course = $mission->getCourse();
                $user = $mission->getUser()->getId();

                if (is_null($orderNumber) && !is_null($mission->getOrderNumber())) {
                    $orderNumber = $mission->getOrderNumber();
                }

                if($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsForInvoice[$user][$mission->getCourse()->getId() . $mission->getStudent()->getId()][] = $mission;
                    $totalAmount += $mission->getHours() * $mission->getStudent()->getHourlyPrice();

                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5
                    
                    for($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTime = new \DateTime;

                        if($i == 0) {
                            $dateTime->setDate(
                            $mission->getBeginAt()->format("Y"),
                            $mission->getBeginAt()->format("m"),
                            $mission->getBeginAt()->format("d"));
                        } else {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i);
                        }

                        $newMission->setBeginAt($dateTime);
                        $totalAmount += $newMission->getHours() * $newMission->getStudent()->getHourlyPrice();
                        $missionsForInvoice[$user][$newMission->getCourse()->getId() . $newMission->getStudent()->getId()][] = $newMission;
                    }

                }

            }

            $date = new \DateTime($year . '-' . $month . '-01');
            $invoiceDate = $date->modify( 'first day of next month' );
            $dateEcheance = new \DateTime($year . '-' . ($month + 1) . '-01');
            $dateEcheance = $dateEcheance->modify( 'first day of next month' );

            $invoiceDate = $invoiceDate->format('d-m-Y');
            $invoiceDateEcheance = $dateEcheance->format('d-m-Y');

            $date = new \DateTime($year . '-' . $month . '-01');
            $invoiceNumber = "F".$date->format("Ym") . '/' . $mission->getClient()->getId();

            $data = [
                'missions'  => $missionsForInvoice,
                'teacher' => $user,
                'invoiceNumber' => $invoiceNumber,
                'invoiceDate' => $invoiceDate,
                'invoiceDateEcheance' => $invoiceDateEcheance,
                'totalAmount' => round($totalAmount),
                'client' => $client,
                'orderNumber' => $orderNumber
            ];
            $html =  $this->renderView('profile/invoices-template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            // dd($missionsForInvoice);

            return new Response (
                $dompdf->stream("Web Start - " . $invoiceNumber . ".pdf", ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/profile/invoices/{year}/{month}/{clientId}/{userId}', name: 'app_generate_invoice_teacher')]
    public function generateInvoiceForOneTeacher(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator, int $year, int $month, int $clientId, int $userId): Response
    {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            $data = $this->getValues($doctrine);

            $dateTime = new \DateTime("now");
            $year = $dateTime->format("Y");
            $missions = $doctrine->getRepository(Mission::class)->findMissionForCustomerAndOneTeacher($year, $month, $clientId, $userId);
            $client = $doctrine->getRepository(Clients::class)->find($clientId);
            $invoice = NULL;
            // va falloir regrouper les missions par formateurs et par cours
            // TODO
            // dd($missions);
            $course = NULL;
            $user = NULL;
            $missionsForInvoice = NULL;
            $totalAmount = NULL;
            $orderNumber = NULL;

            // dd($missions);

            foreach($missions as $mission) {

                $course = $mission->getCourse();
                $user = $mission->getUser()->getId();

                if (is_null($orderNumber) && !is_null($mission->getOrderNumber())) {
                    $orderNumber = $mission->getOrderNumber();
                }

                if($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsForInvoice[$user][$mission->getCourse()->getId() . $mission->getStudent()->getId()][] = $mission;
                    $totalAmount += $mission->getHours() * $mission->getStudent()->getHourlyPrice();

                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5
                    
                    for($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTime = new \DateTime;

                        if($i == 0) {
                            $dateTime->setDate(
                            $mission->getBeginAt()->format("Y"),
                            $mission->getBeginAt()->format("m"),
                            $mission->getBeginAt()->format("d"));
                        } else {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i);
                        }

                        $newMission->setBeginAt($dateTime);
                        $totalAmount += $newMission->getHours() * $newMission->getStudent()->getHourlyPrice();
                        $missionsForInvoice[$user][$newMission->getCourse()->getId() . $newMission->getStudent()->getId()][] = $newMission;
                    }

                }

            }

            $date = new \DateTime($year . '-' . $month . '-01');
            $invoiceDate = $date->modify( 'first day of next month' );
            $dateEcheance = new \DateTime($year . '-' . ($month + 1) . '-01');
            $dateEcheance = $dateEcheance->modify( 'first day of next month' );

            $invoiceDate = $invoiceDate->format('d-m-Y');
            $invoiceDateEcheance = $dateEcheance->format('d-m-Y');

            $date = new \DateTime($year . '-' . $month . '-01');
            $invoiceNumber = "F".$date->format("Ym") . '/' . $mission->getClient()->getId() . '/' . $mission->getUser()->getId() ;

            $data = [
                'missions'  => $missionsForInvoice,
                'teacher' => $user,
                'invoiceNumber' => $invoiceNumber,
                'invoiceDate' => $invoiceDate,
                'invoiceDateEcheance' => $invoiceDateEcheance,
                'totalAmount' => round($totalAmount),
                'client' => $client,
                'orderNumber' => $orderNumber
            ];
            $html =  $this->renderView('profile/invoices-template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            // dd($missionsForInvoice);

            return new Response (
                $dompdf->stream("Web Start - " . $invoiceNumber . ".pdf", ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/profile/opportunities/{id}', name: 'app_profile_application')]
    public function accept_opportunity(Request $request, 
    PersistenceManagerRegistry $doctrine, 
    PaginatorInterface $paginator,
    EntityManagerInterface $entityManager,
    int $id): Response
    {

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


    public function getValues(EntityManagerInterface $doctrine) {

        $userId = $this->getUser()->getId();
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
        $missions = $doctrine->getRepository(Mission::class)->findCaForCurrentYear($year, $userId);
        $ca = null;

        foreach($missions as $mission) {
                
            // si il s'agit d'une mission d'une journée
            // j'ajoute à la liste des missions
            if($mission->getBeginAt() == $mission->getEndAt()) {
                $missionsToDisplay[] = $mission;
                $ca += $mission->getRemuneration();
            } else {
                // si il s'agit d'une mission sur plusieurs jours
                // faut que je décortique la mission
                $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5
                
                for($i = 0; $i < $nbrOfDayForMission; $i++) {
                    $newMission = clone $mission;
                    $dateTime = new \DateTime;

                    if($i == 0) {
                        $dateTime->setDate(
                        $mission->getBeginAt()->format("Y"),
                        $mission->getBeginAt()->format("m"),
                        $mission->getBeginAt()->format("d"));
                    } else {
                        $dateTime->setDate(
                            $mission->getBeginAt()->format("Y"),
                            $mission->getBeginAt()->format("m"),
                            $mission->getBeginAt()->format("d") + $i);
                    }

                    $newMission->setBeginAt($dateTime);
                    $ca += $newMission->getRemuneration();
                }

            }
        }

        return [$year, $ca];

    }

    #[Route('/profile/clients/payment', name: 'app_clients_payments')]
    public function clientsPayment(Request $request, 
    EntityManagerInterface $doctrine, 
    PaginatorInterface $paginator, int $month): Response
    {

 
        return $this->render('profile/payment-clients.html.twig', [

        ]);

    }
    
}
