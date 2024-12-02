<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;


class ContactController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $contact->setIsRead(false);
                $entityManager->persist($contact);
                $entityManager->flush();

                // Envoie d'un email à Formation WS pour le notifier
                $email = (new TemplatedEmail())
                ->from(new Address($this->params->get('app.mail_address_dsn'), 'Formation WS - Contact'))
                ->to($this->params->get('app.mail_address'))
                ->subject('Message de ' . $contact->getFirstname() . ' ' . $contact->getLastname() . ' ' )
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'name' => $contact->getLastName(),
                    'firstname' => $contact->getFirstName(),
                    'adressEmail' => $contact->getEmail(),
                    'phone' => $contact->getPhone(),
                    'message' => $contact->getMessage(),
                    'object' => $contact->getObject(),
                    'currentJob' => $contact->getCurrentJob(),

                ]);

                $mailer->send($email);

                $this->addFlash('success', 'Votre message à bien été envoyé.');
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);

        }


        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'contactForm' => $form->createView(),
        ]);
    }

}
