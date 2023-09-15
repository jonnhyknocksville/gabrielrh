<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class ContractsController extends AbstractController
{
    #[Route('/contracts/{year}/{month}', name: 'app_contracts')]
    public function index(EntityManagerInterface $doctrine, Pdf $knpSnappyPdf, String $year, String $month)
    {

        // je récupère le user
        $userId = $this->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->findBy(['id' => $userId]);

        // je récupère les missions pour le mois en cours
        $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($userId, $year, $month);
        $missionsToDisplay = [];
        $totalAmount = null;
        $totalHours = null;
        foreach($missions as $mission) {
            
            // si il s'agit d'une mission d'une journée
            // j'ajoute à la liste des missions
            // if($mission->getBeginDate() == $mission->getEndDate()) {
            //     $missionsToDisplay[] = $mission;
            // }
            // si il s'agit d'une mission sur plusieurs jours
            // faut que je décortique la mission

        }

        $header = $this->renderView('contracts/header.html.twig');
        $footer = $this->renderView('contracts/footer.html.twig');

        $html = $this->renderView('contracts/template.html.twig', array(
            'missions'  => $missionsToDisplay,
            'teacher' => $user
        ));

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html, array(
                // 'orientation' => 'landscape', 
                // 'enable-javascript' => true, 
                // 'javascript-delay' => 1000, 
                // 'no-stop-slow-scripts' => true, 
                // 'no-background' => false, 
                // 'lowquality' => false,
                'encoding' => 'utf-8',
                // 'images' => true,
                // 'cookie' => array(),
                // 'dpi' => 300,
                // 'image-dpi' => 300,
                // 'enable-external-links' => true,
                // 'enable-internal-links' => true
                'header-html' => $header,
                'footer-html' => $footer,
                'margin-top' => 20,
                'margin-right' => 15,
                'margin-bottom' => 10,
                'margin-left' => 10
            )),
            'file.pdf'
        );


        // return $this->render('contracts/index.html.twig', [
        //     'controller_name' => 'ContractsController',
        // ]);
    }
}
