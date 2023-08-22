<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;


class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Technique du pot de miel pour empêcher les spams
            // permet d'empécher les spams de nous harceler de mail
            if(is_null($form["raison"]->getData()) or empty($form["raison"]->getData())) {

                $contact->setIsRead(false);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();

                // Envoie d'un email à Formation WS pour le notifier
                $email = (new TemplatedEmail())
                ->from(new Address('contact@academiews.fr', 'Formation WS - Contact'))
                ->to('contact@academiews.fr')
                ->subject('Message de ' . $contact->getFirstname() . ' ' . $contact->getLastname() . ' ' )
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'name' => $contact->getLastName(),
                    'firstname' => $contact->getFirstName(),
                    'adressEmail' => $contact->getEmail(),
                    'phone' => ($contact->getPhone() == null) ? "non fourni" : $contact->getPhone(),
                    'message' => $contact->getMessage(),
                    'object' => $contact->getObject(),
                ]);

                $mailer->send($email);

                $this->addFlash('success', 'Votre message à bien été envoyé.');
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        }


        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'contactForm' => $form->createView(),
        ]);
    }

}
