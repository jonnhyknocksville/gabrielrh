<?php

namespace App\Controller;

use App\Entity\Training;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class TrainingController extends AbstractController
{
    #[Route('/training', name: 'app_training')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $trainings = $doctrine->getRepository(Training::class)->findAll();
        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/training/{id}', name: 'app_get_training_details')]
    public function getTraining(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $training = $doctrine->getRepository(Training::class)->find($id);
        return $this->render('training/details.html.twig', [
            'training' => $training,
        ]);
    }

}
