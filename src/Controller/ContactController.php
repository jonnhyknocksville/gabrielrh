<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,EntityManagerInterface $entityManager, MailService $mailService): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();

            // gerer l'envoie de mail

            $mailService->sendMail(
                [
                    'firstName' => $contact->getFirstName(),
                    'name' => $contact->getName(),
                    'message' => $contact->getMessage()
                ],
                $contact->getEmail(),
                'Message de contact',
                'emails/signup.html.twig'
            );                

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);

            $this->addFlash('confirmation', 'votre demande de contact a bien été envoyé !');

        }

        // $this->redirectToRoute('app_contact');

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contact_form' => $form,
        ]);
    }
}
