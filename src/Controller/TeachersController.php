<?php

namespace App\Controller;

use App\Entity\TeacherApplication;
use App\Form\TeacherApplicationType;
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
        $teacherApplication = new TeacherApplication();
        $form = $this->createForm(TeacherApplicationType::class, $teacherApplication);
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

        return $this->render('teachers/opportunity.html.twig', [
            'job_application' => $teacherApplication,
            'applicationForm' => $form->createView(),
        ]);
    }
    
}
