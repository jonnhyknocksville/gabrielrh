<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\ProfessionalsNeeds;
use App\Form\JobsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FindTeachersType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ProfessionalsController extends AbstractController
{
    #[Route('/professionals', name: 'professionals')]
    public function index(): Response
    {
        return $this->render('professionals/index.html.twig', [
        ]);
    }

    #[Route('/professionals/find', name: 'find_teachers')]
    public function find(Request $request, MailerInterface $mailer): Response
    {

        $contact = new ProfessionalsNeeds();
        $form = $this->createForm(FindTeachersType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoie d'un email à Formation WS pour le notifier
            $email = (new TemplatedEmail())
            ->from(new Address('contact@academiews.fr', 'Formation WS - Collaboration'))
            ->to('contact@academiews.fr')
            ->subject('Message de ' . $contact->getFirstname() . ' ' . $contact->getLastname() . ' ' )
            ->htmlTemplate('professionals/email.html.twig')
            ->context([
                'name' => $contact->getLastName(),
                'firstname' => $contact->getFirstName(),
                'adressEmail' => $contact->getEmail(),
                'phone' => ($contact->getPhone() == null) ? "non fourni" : $contact->getPhone(),
                'message' => $contact->getMessage(),
                'poste' => $contact->getPoste(),
                'object' => $contact->getObject(),
            ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('find_teachers', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('professionals/needs.html.twig', [
            'contact' => $contact,
            'teachersForm' => $form->createView(),
        ]);
    }


    #[Route('/professionals/create/mission', name: 'find_teachers')]
    public function create_mission(Request $request,
     MailerInterface $mailer,
     EntityManagerInterface $entityManager,
     PersistenceManagerRegistry $doctrine): Response
    {

        $proMission = new Jobs();
            $form = $this->createForm(JobsType::class, $proMission);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Je définis le nom du CV en BDD pour pouvoir le récupérer esnuite grâce à VichUploader.
                $entityManager = $doctrine->getManager();
                $entityManager->persist($proMission);
                $entityManager->flush();

                // Envoie d'un email à Formation WS pour le notifier
                // $email = (new TemplatedEmail())
                //     ->from(new Address('contact@academiews.fr', 'Formation WS - Recrutement'))
                //     ->to('contact@academiews.fr')
                //     ->subject('Nouvelle demande de Recrutement')
                //     ->htmlTemplate('job_application/email.html.twig')
                //     ->attachFromPath($proMission->getCvFile()->getPath() . "/" . $proMission->getCvFile()->getFilename())
                //     ->context([
                //         'name' => $proMission->getLastName(),
                //         'firstname' => $proMission->getFirstName(),
                //         'adressEmail' => $proMission->getEmail(),
                //         'phone' => $proMission->getPhone(),
                //         'message' => $proMission->getMessage(),
                //         'motif' => $proMission->getMotif()
                //     ]);

                // $mailer->send($email);

                $this->addFlash('success', 'Votre message à bien été envoyé.');
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('professionals/create-mission.html.twig', [
                'missionForm' => $form->createView()
            ]);
        }

    
}
