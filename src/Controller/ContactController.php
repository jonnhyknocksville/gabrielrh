<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,EntityManagerInterface $entityManager, ServiceEntityRepositoryInterface $er): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('confirmation', 'votre demande de contact a bien été envoyé !');
            
            

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('contact/index.html.twig');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contact_form' => $form,
        ]);
    }
}
