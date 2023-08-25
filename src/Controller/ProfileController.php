<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/password', name: 'app_profile_password')]
    public function edit(Request $request, 
    UserPasswordHasherInterface $passwordEncoder, 
    PersistenceManagerRegistry $doctrine,
    EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $form['oldPassword']->getData())) {
                
                // j'encode le mot de passe et je le mets à jour
                $newEncodedPassword = $passwordEncoder->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($newEncodedPassword);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('message', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('app_profile');
            } else {
                $this->addFlash('message', 'La saisie de l\'ancien mot de passe est incorrecte');
            }
        }

        return $this->render('profile/modify-password.html.twig', [
            'changePasswordForm' => $form->createView(),
            'active_link' => 'profile' 
        ]);

    }

    #[Route('/profile/contracts', name: 'app_profile_contracts')]
    public function contracts(Request $request): Response
    {

        return $this->render('profile/contracts.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/skills', name: 'app_profile_skills')]
    public function skills(Request $request, PersistenceManagerRegistry $doctrine,PaginatorInterface $paginator): Response
    {

        $user = $this->getUser();
        $user = $doctrine->getRepository(User::class)->findBy(["id" => $user->getUserIdentifier()])[0];

        $skills = $paginator->paginate(
            $user->getCourses(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('profile/skills.html.twig', [
            'skills' => $skills,
        ]);
    }
    #[Route('/profile/address', name: 'app_profile_address')]
    public function address(Request $request): Response
    {

        return $this->render('profile/address.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    
}
