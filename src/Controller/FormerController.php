<?php

namespace App\Controller;

use App\Entity\Former;
use App\Form\FormerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormerController extends AbstractController
{
    #[Route('/formateur', name: 'app_former')]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $former = new Former();
        $form = $this->createForm(FormerType::class, $former);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $former = $form->getData();
            $entityManager->persist($former);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande a été envoyer');
            return $this->redirectToRoute('app_former');
        }

        return $this->render('former/index.html.twig', [
            'former_form' => $form,
        ]);
    }
}
