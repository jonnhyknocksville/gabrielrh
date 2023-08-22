<?php

namespace App\Controller;

use App\Entity\Courses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class CoursesController extends AbstractController
{

    #[Route('/courses/{id}', name: 'app_courses_details')]
    public function index(PersistenceManagerRegistry $doctrine, int $id): Response
    {

        $course = $doctrine->getRepository(Courses::class)->findBy(['id' => $id]);
        return $this->render('courses/index.html.twig', [
            'course' => $course[0],
        ]);
    }
}
