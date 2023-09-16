<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class ContractsController extends AbstractController
{
    #[Route('/contracts/{year}/{month}', name: 'app_contracts')]
    public function index(EntityManagerInterface $doctrine, Pdf $knpSnappyPdf, String $year, String $month)
    {

        // je récupère le user
        $userId = $this->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->findBy(['id' => $userId])[0];

        // je récupère les missions pour le mois en cours
        $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($userId, 2023, 9);
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
        $header = $this->renderView('contracts/header.html.twig');
        $footer = $this->renderView('contracts/footer.html.twig');

        $html = $this->renderView('contracts/template.html.twig', array(
            'missions'  => $missionsToDisplay,
            'teacher' => $user,
            'bdcNumber' => $bdcNumber,
            'contractNumber' => $contractNumber,
            'contractDate' => $contractDate,
            'totalAmount' => $totalAmount,
            'totalHours' => $totalHours
        ));

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html, array(
                // 'orientation' => 'landscape', 
                // 'enable-javascript' => true, 
                // 'javascript-delay' => 1000, 
                // 'no-stop-slow-scripts' => true, 
                // 'no-background' => false, 
                // 'lowquality' => false,
                'encoding' => 'utf-8',
                // 'images' => true,
                // 'cookie' => array(),
                // 'dpi' => 300,
                // 'image-dpi' => 300,
                // 'enable-external-links' => true,
                // 'enable-internal-links' => true
                'header-html' => $header,
                'footer-html' => $footer,
                'margin-top' => 20,
                'margin-right' => 15,
                'margin-bottom' => 10,
                'margin-left' => 10
            )),
            'file.pdf'
        );
    }
}
