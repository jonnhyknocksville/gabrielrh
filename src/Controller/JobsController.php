<?php

namespace App\Controller;

use App\Entity\JobApplication;
use App\Entity\Jobs;
use App\Form\JobApplicationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class JobsController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/jobs', name: 'app_jobs')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {

        $jobs = $doctrine->getRepository(Jobs::class)->findAll();

        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    #[Route('/jobs/{id}', name: 'app_jobs_details')]
    public function get(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $job = $doctrine->getRepository(Jobs::class)->findBy(['id' => $id]);
        $idTheme = $job[0]->getTheme()->getId();

        $relatedJobs = $doctrine->getRepository(Jobs::class)->findBy(['theme' => $idTheme]);

        return $this->render('jobs/details.html.twig', [
            'job' => $job[0],
            'relatedJobs' => $relatedJobs
        ]);
    }

    #[Route('/jobs/application/{id}', name: 'app_jobs_application')]
    public function apply(Request $request, MailerInterface $mailer, PersistenceManagerRegistry $doctrine, $id): Response
    {
        $jobApplication = new JobApplication();
        $form = $this->createForm(JobApplicationType::class, $jobApplication, ['allow_extra_fields' =>true]);
        $form->handleRequest($request);

        $jobInfo = $doctrine->getRepository(Jobs::class)->findBy(['id' => $id])[0];

        if ($form->isSubmitted() && $form->isValid()) {

            $id_job = $form['job']->getData();
            $job = $doctrine->getRepository(Jobs::class)->findBy(['id' => $id_job])[0];
            $jobApplication->setJob($job);
            $jobApplication->setNamerCV($jobApplication->getLastName() . "_" . $jobApplication->getFirstName());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($jobApplication);
            $entityManager->flush();

            // Envoie d'un email à Formation WS pour le notifier
            // $email = (new TemplatedEmail())
            //     ->from(new Address($this->params->get('app.mail_address'), 'Formation WS - Recrutement'))
            //     ->to($this->params->get('app.mail_address'))
            //     ->subject('Nouvelle demande de Recrutement')
            //     ->htmlTemplate('job_application/email.html.twig')
            //     ->attachFromPath($jobApplication->getCvFile()->getPath() . "/" . $jobApplication->getCvFile()->getFilename())
            //     ->context([
            //         'name' => $jobApplication->getLastName(),
            //         'firstname' => $jobApplication->getFirstName(),
            //         'adressEmail' => $jobApplication->getEmail(),
            //         'phone' => $jobApplication->getPhone(),
            //         'message' => $jobApplication->getMessage(),
            //         'motif' => $jobApplication->getMotif()
            //     ]);

            // $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jobs/application.html.twig', [
            'job_application' => $jobApplication,
            'jobInfo' => $jobInfo,
            'id_job' => $id,
            'applicationForm' => $form->createView(),
        ]);
    }
}
