<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request): Response
    {

        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'ProfileController',
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
    public function skills(Request $request): Response
    {

        return $this->render('profile/skills.html.twig', [
            'controller_name' => 'ProfileController',
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
