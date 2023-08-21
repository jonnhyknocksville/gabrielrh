<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    #[Route('/jobs', name: 'app_jobs')]
    public function index(): Response
    {
        return $this->render('jobs/index.html.twig', [
            'controller_name' => 'JobsController',
        ]);
    }

    #[Route('/jobs/{id}', name: 'app_jobs_details')]
    public function get(): Response
    {
        return $this->render('jobs/details.html.twig', [
            'controller_name' => 'JobsController',
        ]);
    }
}
