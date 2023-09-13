<?php

namespace App\Controller;

use App\Entity\ProfessionalsNeeds;
use App\Entity\Themes;
use App\Form\FindTeachersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class HomeController extends AbstractController
{
    // cette route permet de gerer la page home
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $need = new ProfessionalsNeeds();
        $form = $this->createForm(FindTeachersType::class, $need);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('find_teachers_pre_field', 
            array('profile' => $need->getProfil(), 
            'date' => $need->getDate()->format('Y-m-d'), 
            'city' => $need->getLocalisation(), 
            'theme' => $need->getTheme()->getId() ));


        }

        // Récupération des thèmes
        $themes = $doctrine->getRepository(Themes::class)->findMax6();
        return $this->render('home/index.html.twig', [
            'themes' => $themes,
            'pro_need' => $form
        ]);
    }
}
