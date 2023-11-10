<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Mission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class ContractsController extends AbstractController
{
    #[Route('/contracts/{year}/{month}', name: 'app_contracts')]
    public function index(EntityManagerInterface $doctrine, String $year, String $month) : Response
    {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            // je récupère le user
            $userId = $this->getUser()->getId();
            $user = $doctrine->getRepository(User::class)->findBy(['id' => $userId])[0];

            // je récupère les missions pour le mois en cours
            $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($userId, $year, $month);
            $missionsToDisplay = [];
            $totalAmount = null;
            $totalHours = null;
            foreach($missions as $mission) {
                
                // si il s'agit d'une mission d'une journée
                // j'ajoute à la liste des missions
                if($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsToDisplay[] = $mission;
                    $totalAmount += $mission->getRemuneration();
                    $totalHours += $mission->getHours();
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
                        $totalAmount += $newMission->getRemuneration();
                        $totalHours += $newMission->getHours();
                        $missionsToDisplay[] = $newMission;
                    }

                }

            
            }

            $contractDate = new \DateTime;
            $contractDate->setDate($mission->getBeginAt()->format("Y"), $mission->getBeginAt()->format("m"), 1);
            $bdcNumber = "B".$contractDate->format("Ym") . $userId;
            $contractNumber ="C". $contractDate->format("Ym") . $userId;

            $data = [
                'missions'  => $missionsToDisplay,
                'teacher' => $user,
                'bdcNumber' => $bdcNumber,
                'contractNumber' => $contractNumber,
                'contractDate' => $contractDate,
                'totalAmount' => $totalAmount,
                'totalHours' => $totalHours
            ];
            $html =  $this->renderView('contracts/template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response (
                $dompdf->stream("Web Start - " . $bdcNumber, ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }


    // Méthode permettant de générer le contrat de prestations de service pour un client pour l'année en cours
    #[Route('/contracts/{clientId}/{year}', name: 'app_contracts_b2b_for_current_year')]
    public function generate_contrats_for_year(EntityManagerInterface $doctrine, int $clientId = 2, int $year = 2023 ) : Response
    {

        $client = $doctrine->getRepository(Clients::class)->find($clientId);
        $courses = $doctrine->getRepository(Mission::class)->findDifferentCoursesForClientAndYear($clientId, $year);
        $teachers = $doctrine->getRepository(Mission::class)->findDistinctTeachersForCustomers($clientId, $year);
        $teachersAndCourses = $doctrine->getRepository(Mission::class)->findDifferentTeachersForClientAndCourseAndYear($clientId, $year);

        $data = [
            // 'missions'  => $missionsForInvoice,
            // 'teacher' => $user,
            // 'invoiceNumber' => $invoiceNumber,
            // 'invoiceDate' => $invoiceDate,
            // 'invoiceDateEcheance' => $invoiceDateEcheance,
            // 'totalAmount' => round($totalAmount, 0),
            // 'client' => $client,
            // 'orderNumber' => $orderNumber
            'courses' => $courses,
            'teachers' => $teachers,
            'teachersAndCourses' => $teachersAndCourses,
            'client' => $client,
            'nextYear' => $year + 1,
            'year' => $year,
        ];

        $html =  $this->renderView('contracts/year.html.twig', $data);
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response (
            $dompdf->stream("Web Start - "),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    
    }

    #[Route('/contract/admin/download/{teacherId}/{month}/{year}', name: 'app_contracts_admin_download')]
    public function contracts_admin_download(Request $request, 
    EntityManagerInterface $doctrine, int $teacherId, int $month, int $year):Response
    {

        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {

            $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($teacherId, $year, $month);

            $user = $doctrine->getRepository(User::class)->findBy(['id' => $teacherId])[0];

            $missionsToDisplay = [];
            $totalAmount = null;
            $totalHours = null;
            foreach($missions as $mission) {
                
                // si il s'agit d'une mission d'une journée
                // j'ajoute à la liste des missions
                if($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsToDisplay[] = $mission;
                    $totalAmount += $mission->getRemuneration();
                    $totalHours += $mission->getHours();
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
                        $totalAmount += $newMission->getRemuneration();
                        $totalHours += $newMission->getHours();
                        $missionsToDisplay[] = $newMission;
                    }

                }

            
            }

            $contractDate = new \DateTime;
            $contractDate->setDate($mission->getBeginAt()->format("Y"), $mission->getBeginAt()->format("m"), 1);
            $bdcNumber = "B".$contractDate->format("Ym") . $teacherId;
            $contractNumber ="C". $contractDate->format("Ym") . $teacherId;

            $data = [
                'missions'  => $missionsToDisplay,
                'teacher' => $user,
                'bdcNumber' => $bdcNumber,
                'contractNumber' => $contractNumber,
                'contractDate' => $contractDate,
                'totalAmount' => $totalAmount,
                'totalHours' => $totalHours
            ];
            $html =  $this->renderView('contracts/template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response (
                $dompdf->stream("Web Start - " . $bdcNumber, ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        
        }else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

    }
}
