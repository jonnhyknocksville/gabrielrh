<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Form\JobsType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    #[Route('/jobs/new', name: 'create_job')]
    public function createJob(Request $request, EntityManagerInterface $entityManager): Response
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

        return $this->render('jobs/new.html.twig', [
            'controller_name' => 'JobsController',
            'add_jobs_form' => $form
        ]);
    }

    #[Route('/jobs', name: 'app_jobs')]

    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $jobs = $em->getRepository(Jobs::class)->findBy([],['updated_on' => 'desc']);

        $jobs = $paginator->paginate(
                $jobs,
                $request->query->getInt('page', 1), /*page number*/
                2 /*limit per page*/
        );

        return $this->render('jobs/index.html.twig', [
            'listJobs' => $jobs,
            'pagination' => $jobs
        ]);
    }
}
