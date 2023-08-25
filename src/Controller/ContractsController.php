<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractsController extends AbstractController
{
    #[Route('/contracts', name: 'app_contracts')]
    public function index(): Response
    {
        return $this->render('contracts/index.html.twig', [
            'controller_name' => 'ContractsController',
        ]);
    }
}
