<?php

namespace App\Controller;

use App\Entity\ProfessionalsNeeds;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FindTeachersType;

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

    
}
