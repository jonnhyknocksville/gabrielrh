<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\ProfessionalsNeeds;
use App\Entity\Themes;
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
            ->from(new Address($this->params->get('app.mail_address_dsn'), 'Formation WS - Collaboration'))
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
            'prefilled' => false
        ]);
    }

    #[Route('/professionals/find/{profile}/{date}/{city}/{theme}', name: 'find_teachers_pre_field')]
    public function preField(Request $request, 
    MailerInterface $mailer, 
    EntityManagerInterface $entityManager,
    PersistenceManagerRegistry $doctrine,
    $profile, $date, $city, $theme): Response
    {

        $need = new ProfessionalsNeeds();

        $need->setProfil($profile);
        $need->setDate(new \DateTime($date));
        $need->setLocalisation($city);
        $theme = $doctrine->getRepository(Themes::class)->findBy(["id" => $theme])[0];
        $need->setTheme($theme);
        $need->setMotive('J\'ai besoin de formateurs');
        $need->setMessage('Pouvez-vous me trouver un formateur en... ');
        
        $form = $this->createForm(FindTeachersType::class, $need);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($need);
            $entityManager->flush();

            // Envoie d'un email à Formation WS pour le notifier
            $email = (new TemplatedEmail())
            ->from(new Address($this->params->get('app.mail_address_dsn'), 'Formation WS - Collaboration'))
            ->to($this->params->get('app.mail_address'))
            ->subject('Message de ' . $need->getFirstname() . ' ' . $need->getLastname() . ' ' )
            ->htmlTemplate('professionals/email.html.twig')
            ->context([
                'name' => $need->getLastName(),
                'firstname' => $need->getFirstName(),
                'adressEmail' => $need->getEmail(),
                'phone' => ($need->getPhone() == null) ? "non fourni" : $need->getPhone(),
                'message' => $need->getMessage(),
                'poste' => $need->getCurrentJob(),
                'object' => $need->getMotive(),
            ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professionals/needs.html.twig', [
            'contact' => $need,
            'teachersForm' => $form->createView(),
            'prefilled' => true
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
                //     ->from(new Address($this->params->get('app.mail_address_dsn'), 'Formation WS - Recrutement'))
                //     ->to($this->params->get('app.mail_address_dsn'))
                //     ->subject('Une mission a été créer')
                //     ->htmlTemplate('professionals/email-mission.html.twig')
                //     ->context([
                //         'title' => $proMission->getTitle(),
       

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
