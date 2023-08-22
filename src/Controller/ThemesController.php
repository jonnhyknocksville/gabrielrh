<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Themes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ThemesController extends AbstractController
{
    #[Route('/themes', name: 'app_themes')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        // Récupération des thèmes
        $themes = $doctrine->getRepository(Themes::class)->findAll();
        return $this->render('themes/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/themes/{id}', name: 'app_themes_details')]
    public function get(PersistenceManagerRegistry $doctrine, $id): Response
    {

        // Récupération des courses
        $themes = $doctrine->getRepository(Courses::class)->findBy(['theme' => $id]);

        return $this->render('themes/details.html.twig', [
            'themes' => $themes,
        ]);
    }
}
