<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalMentionController extends AbstractController
{
    #[Route('/legal/mention', name: 'app_legal_mention')]
    public function index(): Response
    {
        return $this->render('legal_mention/index.html.twig', [
            'controller_name' => 'LegalMentionController',
        ]);
    }
}
