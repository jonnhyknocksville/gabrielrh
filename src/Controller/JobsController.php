<?php

namespace App\Controller;

use App\Entity\JobApplication;
use App\Entity\Jobs;
use App\Form\JobApplicationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class JobsController extends AbstractController
{
    #[Route('/jobs', name: 'app_jobs')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {

        $jobs = $doctrine->getRepository(Jobs::class)->findAll();

        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    #[Route('/jobs/{id}', name: 'app_jobs_details')]
    public function get(): Response
    {
        return $this->render('jobs/details.html.twig', [
            'controller_name' => 'JobsController',
        ]);
    }

    #[Route('/jobs/application/{id}', name: 'app_jobs_application')]
    public function apply(Request $request, MailerInterface $mailer): Response
    {
        $teacherApplication = new JobApplication();
        $form = $this->createForm(JobApplicationType::class, $teacherApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Je définis le nom du CV en BDD pour pouvoir le récupérer esnuite grâce à VichUploader.
            $teacherApplication->setNamerCV('CV ' . $teacherApplication->getLastName() . " " . $teacherApplication->getFirstName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacherApplication);
            $entityManager->flush();

            // Envoie d'un email à Académie WS pour le notifier
            $email = (new TemplatedEmail())
                ->from(new Address('contact@academiews.fr', 'Académie WS - Recrutement'))
                ->to('contact@academiews.fr')
                ->subject('Nouvelle demande de Recrutement')
                ->htmlTemplate('job_application/email.html.twig')
                ->attachFromPath($teacherApplication->getCvFile()->getPath() . "/" . $teacherApplication->getCvFile()->getFilename())
                ->context([
                    'name' => $teacherApplication->getLastName(),
                    'firstname' => $teacherApplication->getFirstName(),
                    'adressEmail' => $teacherApplication->getEmail(),
                    'phone' => $teacherApplication->getPhone(),
                    'message' => $teacherApplication->getMessage(),
                    'motif' => $teacherApplication->getMotif()
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jobs/application.html.twig', [
            'job_application' => $teacherApplication,
            'applicationForm' => $form->createView(),
        ]);
    }
}
