<?php

namespace App\Controller;

use App\Entity\TeacherApplication;
use App\Form\TeacherApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class TeachersController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/teachers', name: 'teachers')]
    public function index(): Response
    {
        return $this->render('teachers/index.html.twig', [
            'controller_name' => 'TeachersController',
        ]);
    }


    #[Route('/teachers/opportunity', name: 'teacher_opportunity', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $teacherApplication = new TeacherApplication();
        $form = $this->createForm(TeacherApplicationType::class, $teacherApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Je définis le nom du CV en BDD pour pouvoir le récupérer esnuite grâce à VichUploader.
            $teacherApplication->setNamerCV('CV ' . $teacherApplication->getLastName() . " " . $teacherApplication->getFirstName());
            $entityManager->persist($teacherApplication);
            $entityManager->flush();

            // Envoie d'un email à Formation WS pour le notifier
            $email = (new TemplatedEmail())
                ->from(new Address($this->params->get('app.mail_address_dsn'), 'Formation WS - Recrutement'))
                ->to($this->params->get('app.mail_address'))
                ->subject('Nouvelle demande de Recrutement')
                ->htmlTemplate('teachers/email.html.twig')
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
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teachers/opportunity.html.twig', [
            'job_application' => $teacherApplication,
            'applicationForm' => $form->createView(),
        ]);
    }
    
}
