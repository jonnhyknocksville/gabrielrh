<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\ProfessionalsNeeds;
use App\Entity\Themes;
use App\Entity\User;
use App\Form\FindTeachersType;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $need = new ProfessionalsNeeds();
        $form = $this->createForm(FindTeachersType::class, $need);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('find_teachers_pre_field', 
            array('profile' => $need->getProfil(), 
            'date' => $need->getDate()->format('Y-m-d'), 
            'city' => $need->getLocalisation(), 
            'theme' => $need->getTheme()->getId() ));


        }

        // je récupère le user
        $userId = $this->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->findBy(['id' => $userId])[0];

        // je récupère les missions pour le mois en cours
        $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($userId, 2023, 9);
        // dd($missions);
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

                $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5
                
                // dd($mission->getBeginAt());

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
                    // dd($newMission);
                }

            }

            
            // si il s'agit d'une mission sur plusieurs jours
            // faut que je décortique la mission

        }
        // dd($missionsToDisplay);
        // Récupération des thèmes
        // $themes = $doctrine->getRepository(Themes::class)->findMax6();
        $contractDate = new \DateTime;
        $contractDate->setDate($mission->getBeginAt()->format("Y"), $mission->getBeginAt()->format("m"), 1);
        $bdcNumber = "B".$contractDate->format("Ym") . $userId;
        $contractNumber ="C". $contractDate->format("Ym") . $userId;
        return $this->render('home/index2.html.twig', [
            'missions' => $missionsToDisplay,
            'teacher' => $user,
            'totalAmount' => $totalAmount,
            'totalHours' => $totalHours,
            'contractDate' => $contractDate,
            'bdcNumber' => $bdcNumber,
            'contractNumber' => $contractNumber
        ]);
    }
}
