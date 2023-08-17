<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ElearningController extends AbstractController
{
    #[Route('/elearning', name: 'app_elearning')]
    public function index(): Response
    {
        return $this->render('elearning/index.html.twig', [
            'controller_name' => 'ElearningController',
        ]);
    }
}
