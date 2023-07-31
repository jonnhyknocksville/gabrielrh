<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookieController extends AbstractController
{
    #[Route('/cookie', name: 'app_cookie')]
    public function index(): Response
    {
        return $this->render('cookie/index.html.twig', [
            'controller_name' => 'CookieController',
        ]);
    }
}
