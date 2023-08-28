<?php

namespace App\Controller;

use App\Entity\FaqTeachers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class TeacherProcessController extends AbstractController
{
    #[Route('/teachers/process', name: 'app_teachers_process')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {   

        $faqTeachers = $doctrine->getRepository(FaqTeachers::class)->findAll();
        return $this->render('teacher_process/index.html.twig', [
            'faqTeachers' => $faqTeachers,
        ]);
    }
}
