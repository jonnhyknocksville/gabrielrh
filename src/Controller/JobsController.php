<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Form\JobsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    #[Route('/jobs', name: 'app_jobs')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $job = new Jobs();
        $form = $this->createForm(JobsType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $job = $form->getData();
            $entityManager->persist($job);
            $entityManager->flush();

            $this->addFlash('confirmation', 'votre job a bien été creer !');
            return $this->redirectToRoute('app_jobs');
            
        }

        return $this->render('jobs/index.html.twig', [
            'controller_name' => 'JobsController',
            'jobs_form' => $form
        ]);
    }
}
