<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class TeachersController extends AbstractController
{
    #[Route('/teachers', name: 'teachers')]
    public function index(): Response
    {
        return $this->render('teachers/index.html.twig', [
            'controller_name' => 'TeachersController',
        ]);
    }


    #[Route('/teacher/opportunity', name: 'teacher_opportunity', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $jobApplication = new JobApplication();
        $form = $this->createForm(JobApplicationType::class, $jobApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Je définis le nom du CV en BDD pour pouvoir le récupérer esnuite grâce à VichUploader.
            $jobApplication->setNamerCV('CV ' . $jobApplication->getLastName() . " " . $jobApplication->getFirstName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jobApplication);
            $entityManager->flush();

            // Envoie d'un email à Académie WS pour le notifier
            $email = (new TemplatedEmail())
                ->from(new Address('contact@academiews.fr', 'Académie WS - Recrutement'))
                ->to('contact@academiews.fr')
                ->subject('Nouvelle demande de Recrutement')
                ->htmlTemplate('job_application/email.html.twig')
                ->attachFromPath($jobApplication->getCvFile()->getPath() . "/" . $jobApplication->getCvFile()->getFilename())
                ->context([
                    'name' => $jobApplication->getLastName(),
                    'firstname' => $jobApplication->getFirstName(),
                    'adressEmail' => $jobApplication->getEmail(),
                    'phone' => $jobApplication->getPhone(),
                    'message' => $jobApplication->getMessage(),
                    'motif' => $jobApplication->getMotif()
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teachers/opportunity.html.twig', [
            'job_application' => $jobApplication,
            'applicationForm' => $form->createView(),
        ]);
    }
    
}
