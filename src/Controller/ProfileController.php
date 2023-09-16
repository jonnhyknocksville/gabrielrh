<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\Mission;
use App\Entity\StaffApplication;
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
    public function contracts(Request $request, EntityManagerInterface $doctrine): Response
    {


        $userId = $this->getUser()->getId();
        $dateTime = new \DateTime("now");
        $year = $dateTime->format("Y");
        $missions = $doctrine->getRepository(Mission::class)->findAnnualMissions($userId, $year);
        // dd($missions);
        return $this->render('profile/contracts.html.twig', [
            'contracts' => $missions,
            'userId' => $userId,
            'year' => $year
        ]);
    }

    #[Route('/profile/contracts/admin', name: 'app_contracts_admin')]
    public function contracts_admin(Request $request, EntityManagerInterface $doctrine): Response
    {

        $teachers = $doctrine->getRepository(User::class)->findAll();

        // dd($missions);
        return $this->render('profile/contracts_admin.html.twig', [
            'teachers' => $teachers
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
    #[Route('/profile/infos', name: 'app_profile_infos')]
    public function address(Request $request): Response
    {



        return $this->render('profile/address.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/opportunities', name: 'app_jobs_for_you')]
    public function opportunities(Request $request, 
    PersistenceManagerRegistry $doctrine, 
    PaginatorInterface $paginator): Response
    {

        $user = $this->getUser();
        $user = $doctrine->getRepository(User::class)->findBy(["id" => $user->getUserIdentifier()])[0];
        
        $listCoursesId = [];

        foreach($user->getCourses() as $course) {
            $listCoursesId[] = $course->getId();
        }

        foreach ($listCoursesId as $key => $val) {
            $listCoursesId[$key] = "'". $val . "'";
        }

        $in = implode("," , $listCoursesId);
        $jobs = $doctrine->getRepository(Jobs::class)->findJobsByCourses($in);

        $staffApplication = $doctrine->getRepository(StaffApplication::class)->findBy(["user" => $this->getUser()->getUserIdentifier()]);
        $listJobsApplied = [];
        foreach ($staffApplication as $val) {
            $listJobsApplied[] = $val->getJob()->getId();
        }

        return $this->render('profile/opportunities.html.twig', [
            'jobs' => $jobs,
            'staffApplication' => $staffApplication,
            'listJobsApplied' => $listJobsApplied
        ]);
    }



    #[Route('/profile/opportunities/{id}', name: 'app_profile_application')]
    public function accept_opportunity(Request $request, 
    PersistenceManagerRegistry $doctrine, 
    PaginatorInterface $paginator,
    EntityManagerInterface $entityManager,
    int $id): Response
    {

        $staffApplication = new StaffApplication();
        $job = $doctrine->getRepository(Jobs::class)->findBy(["id" => $id])[0];
        $staffApplication->setDate(new \DateTime);
        $staffApplication->setUser($this->getUser());
        $staffApplication->setJob($job);

        $entityManager->persist($staffApplication);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez bien été postulé pour cette mission !');

        return $this->redirectToRoute('app_jobs_for_you');

    }

    
}
