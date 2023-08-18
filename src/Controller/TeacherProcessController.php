<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherProcessController extends AbstractController
{
    #[Route('/teachers/process', name: 'app_teacher_process')]
    public function index(): Response
    {
        return $this->render('teacher_process/index.html.twig', [
            'controller_name' => 'TeacherProcessController',
        ]);
    }
}
