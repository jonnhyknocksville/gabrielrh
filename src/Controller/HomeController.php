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
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $contact = new ProfessionalsNeeds();
        $form = $this->createForm(FindTeachersType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        // Récupération des thèmes
        $themes = $doctrine->getRepository(Themes::class)->findAll();
        return $this->render('home/index.html.twig', [
            'themes' => $themes,
            'pro_need' => $form
        ]);
    }
}
