<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Themes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ThemesController extends AbstractController
{
    #[Route('/themes', name: 'app_themes')]
    public function index(Request $request,
    PersistenceManagerRegistry $doctrine,
    PaginatorInterface $paginator): Response
    {
        // Récupération des thèmes
        $themes = $doctrine->getRepository(Themes::class)->findAll();

        $themes = $paginator->paginate(
            $themes, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('themes/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/themes/{id}', name: 'app_themes_details')]
    public function get(Request $request, 
    PersistenceManagerRegistry $doctrine, 
    $id, PaginatorInterface $paginator): Response
    {

        // Récupération des courses
        $themes = $doctrine->getRepository(Courses::class)->findBy(['theme' => $id]);

        $themes = $paginator->paginate(
            $themes, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('themes/details.html.twig', [
            'themes' => $themes,
        ]);
    }
}
