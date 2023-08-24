<?php

namespace App\Controller;

use App\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class CalendarController extends AbstractController
{
    #[Route('/teacher/calendar', name: 'app_teacher_calendar')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $user = $this->getUser();
        $missions = $doctrine->getRepository(Mission::class)->findBy(['user' => $user->getUserIdentifier()]);
        $miss = [];
        foreach($missions as $mission) {
            $miss[] = [
                "id" => $mission->getId(),
                "title" => $mission->getClient()->getName() . " - " . $mission->getCourse()->getTitle() ,
                "start" => $mission->getBeginAt()->format("Y-m-d"),
                "end" => $mission->getEndAt()->format("Y-m-d"),
                "url" => $request->getUri() . "/". $mission->getId()
            ];
        }
        $data = json_encode($miss);
        return $this->render('calendar/index.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/teacher/calendar/{id}', name: 'app_teacher_calendar_detail')]
    public function get(Request $request, PersistenceManagerRegistry $doctrine, $id): Response
    {

        $user = $this->getUser();
        $missions = $doctrine->getRepository(Mission::class)->findBy(['user' => $user->getUserIdentifier()]);
        $miss = [];

        $missionDetail = $doctrine->getRepository(Mission::class)->findBy(['id' => $id])[0];
        
        // rediriger si je suis pas le propriétaire de cette intervention
        if($user->getUserIdentifier() != $missionDetail->getUser()->getId()) {
            return $this->redirectToRoute('app_teacher_calendar');
        }

        foreach($missions as $mission) {
            $miss[] = [
                "id" => $mission->getId(),
                "title" => $mission->getClient()->getName() . " - " . $mission->getCourse()->getTitle() ,
                "start" => $mission->getBeginAt()->format("Y-m-d"),
                "end" => $mission->getEndAt()->format("Y-m-d"),
                "url" => $request->getUri()
            ];
        }

        $data = json_encode($miss);
        return $this->render('calendar/index.html.twig', [
            'data' => $data,
            'mission' => $missionDetail
        ]);
    }
}
