<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\User;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class PublicCalendarController extends AbstractController
{
    #[Route('/public/calendar/{firstName}', name: 'app_public_calendar')]
    public function index(String $firstName, Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $user = $doctrine->getRepository(User::class)->findBy(['firstName' => $firstName])[0];
        $missions = $doctrine->getRepository(Mission::class)->findBy(['user' => $user->getUserIdentifier()]);
        $miss = [];
        $missionDetail = $doctrine->getRepository(Mission::class)->findBy(['user' => $user])[0];

        // rediriger si je suis pas le propriétaire de cette intervention
        if($user->getUserIdentifier() != $missionDetail->getUser()->getId()) {
            return $this->redirectToRoute('app_teacher_calendar');
        }

        foreach($missions as $mission) {
            $miss[] = [
                "title" => $mission->getCourse()->getTitle() ,
                "start" => $mission->getBeginAt()->format("Y-m-d"),
                "end" => $mission->getEndAt()->add(new DateInterval('P1D'))->format("Y-m-d"),
                "backgroundColor" => $mission->getClient()->getBackgroundColor()
            ];
        }

        $data = json_encode($miss);
        return $this->render('public_calendar/index.html.twig', [
            'data' => $data,
            'mission' => $missionDetail
        ]);
    }
}
