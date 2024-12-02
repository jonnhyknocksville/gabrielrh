<?php

// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {

    }

    #[Route('/students', name: 'students_list')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer tous les utilisateurs ayant le rôle 'student'
        $teachers = $userRepository->findByRole('ROLE_TEACHER');

        
        $teachers = $paginator->paginate(
            $teachers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('user/index.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    #[Route('/teachers/{id}', name: 'student_detail', methods: ['GET'])]
    public function detail(int $id, UserRepository $userRepository): JsonResponse
    {
        $teacher = $userRepository->find($id);

        if (!$teacher || !in_array('ROLE_TEACHER', $teacher->getRoles())) {
            return new JsonResponse(['error' => 'Teacher not found or does not have the required role.'], Response::HTTP_NOT_FOUND);
        }

        // Retourner les détails complets de l'utilisateur avec le rôle de teacher
        return new JsonResponse([
            'id' => $teacher->getId(),
            'firstName' => $teacher->getFirstName(),
            'lastName' => $teacher->getLastName(),
            'email' => $teacher->getEmail(),
            'siret' => $teacher->getSiret(),
            'address' => $teacher->getAddress(),
            'company' => $teacher->getCompany(),
            'legalForm' => $teacher->getLegalForm(),
            'phone' => $teacher->getPhone(),
            'postalCode' => $teacher->getPostalCode(),
            'city' => $teacher->getCity(),
            'naf' => $teacher->getNaf(),
            'iban' => $teacher->getIban(),
            'kbis' => $teacher->getKbis() ? "/uploads/kbis/" . $teacher->getKbis() : null,
            'attestationVigilance' => $teacher->getAttestationVigilance() ? "/uploads/vigilance/" . $teacher->getAttestationVigilance() : null,
            'criminalRecord' => $teacher->getCriminalRecord() ? "/uploads/criminalRecords/" . $teacher->getCriminalRecord() : null,
            'diplomas' => $teacher->getDiplomas() ? "/uploads/diplomas/" . $teacher->getDiplomas() : null,
            'cv' => $teacher->getCv() ? "/uploads/cv/" . $teacher->getCv() : null,
            'attestationCompetence' => $teacher->getAttestationCompetence() ? "/uploads/competence/" . $teacher->getAttestationCompetence() : null,
            // Ajoutez d'autres champs si nécessaire
        ]);
    }
}


?>