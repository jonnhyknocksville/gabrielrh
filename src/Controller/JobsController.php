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

    // je récupère un job
    #[Route('/jobs/{id}', name: 'show_job_by_id', requirements: ['id' => '\d+'])]
    public function showJob(EntityManagerInterface $entityManager, string $id, Request $request): Response
    {

        // récupérer le job en bdd avec l'id de mon article
        // comment récupérer l'id (qui est param dans l'url)
        // je récupère le paramètre id via l'argument $id

        $idJob = $request->get("id");
        $job = $entityManager->getRepository(Jobs::class)->find($idJob);

        return $this->render('jobs/job.html.twig', [
            'job' => $job
        ]);
    }

    /**
     * CETTE MÉTHODE PERMET DE MODIFIER UN JOB
     */
    #[Route('/jobs/{id}/modify', name: 'modify_job', requirements: ['id' => '\d+'])]
    public function modifyJob(EntityManagerInterface $entityManager, string $id, Request $request):Response {

        // faut récupérer le job en BDD qui à l'id $id
        // ensuite créer le formulaire via JobType
        // render la page jobs/job-modify.html.twig
        // faut render le formulaire dans cette page

        $job = $entityManager->getRepository(Jobs::class)->find($id); // récupère le job en BDD
        $form = $this->createForm(JobsType::class, $job);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($job);
            $entityManager->flush();

        $form = $this->createForm(JobsType::class, $job);

            $this->addFlash('confirmation', 'votre job a bien été modifié en BDD !');
            return $this->redirectToRoute('app_jobs');

        }

        return $this->render('jobs/modify.html.twig', [
            'modify_job_form' => $form->createView(),
            'job' => $job
        ]);

    }

    #[Route('/jobs/{id}/delete', name: 'delete_job', requirements: ['id' => '\d+'])]
    public function deleteJob(EntityManagerInterface $entityManager, string $id, Request $request): Response {


        // si j'ai un post
        // je récupère le paramètre POST ID
        $id = $request->get('id');
        $article = $entityManager->getRepository(Articles::class)->find($id);

        $entityManager->remove($article);
        $entityManager->flush();

        // rediriger vers la page d'accueil avec un msg de confirmation

        $this->addFlash('confirmation', 'Le job a bien été supprimé !');
        return $this->redirectToRoute('app_jobs');

    }


}
