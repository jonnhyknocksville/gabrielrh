<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\ProfessionalsNeeds;
use App\Form\JobsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FindTeachersType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ProfessionalsController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/professionals', name: 'professionals')]
    public function index(): Response
    {
        return $this->render('professionals/index.html.twig', [
        ]);
    }

    #[Route('/professionals/find', name: 'find_teachers')]
    public function find(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {

        $contact = new ProfessionalsNeeds();
        $form = $this->createForm(FindTeachersType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoie d'un email à Formation WS pour le notifier
            $email = (new TemplatedEmail())
            ->from(new Address($this->params->get('app.mail_address'), 'Formation WS - Collaboration'))
            ->to($this->params->get('app.mail_address'))
            ->subject('Message de ' . $contact->getFirstname() . ' ' . $contact->getLastname() . ' ' )
            ->htmlTemplate('professionals/email.html.twig')
            ->context([
                'name' => $contact->getLastName(),
                'firstname' => $contact->getFirstName(),
                'adressEmail' => $contact->getEmail(),
                'phone' => ($contact->getPhone() == null) ? "non fourni" : $contact->getPhone(),
                'message' => $contact->getMessage(),
                'poste' => $contact->getCurrentJob(),
                'object' => $contact->getMotive(),
            ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('professionals/needs.html.twig', [
            'contact' => $contact,
            'teachersForm' => $form->createView(),
        ]);
    }


    #[Route('/professionals/create/mission', name: 'app_create_mission')]
    public function create_mission(Request $request,
     MailerInterface $mailer,
     EntityManagerInterface $entityManager,
     PersistenceManagerRegistry $doctrine): Response
    {

        $proMission = new Jobs();
            $form = $this->createForm(JobsType::class, $proMission);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                // arranger les données

                $entityManager = $doctrine->getManager();
                $entityManager->persist($proMission);
                $entityManager->flush();

                // Envoie d'un email à Formation WS pour le notifier
                // $email = (new TemplatedEmail())
                //     ->from(new Address($this->params->get('app.mail_address'), 'Formation WS - Recrutement'))
                //     ->to($this->params->get('app.mail_address'))
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
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('professionals/create-mission.html.twig', [
                'missionForm' => $form->createView()
            ]);
        }

    
}
