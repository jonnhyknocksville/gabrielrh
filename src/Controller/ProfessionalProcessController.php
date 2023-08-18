<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionalProcessController extends AbstractController
{
    #[Route('/professional/process', name: 'app_professional_process')]
    public function index(): Response
    {
        return $this->render('professional_process/index.html.twig', [
            'controller_name' => 'ProfessionalProcessController',
        ]);
    }
}
