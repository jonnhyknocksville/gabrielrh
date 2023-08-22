<?php

namespace App\Controller;

use App\Entity\Themes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {

        // Récupération des thèmes
        $themes = $doctrine->getRepository(Themes::class)->findAll();
        return $this->render('home/index.html.twig', [
            'themes' => $themes,
        ]);
    }
}
