<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Contract;
use App\Entity\Mission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class ContractsController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/contracts/{year}/{month}', name: 'app_contracts')]
    public function index(EntityManagerInterface $doctrine, string $year, string $month): Response
    {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles(), true)) {

            // je récupère le user
            $userId = $this->getUser()->getId();
            $user = $doctrine->getRepository(User::class)->findBy(['id' => $userId])[0];

            // je récupère les missions pour le mois en cours
            $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($userId, $year, $month);
            $missionsToDisplay = [];
            $totalAmount = null;
            $totalHours = null;
            foreach ($missions as $mission) {

                // si il s'agit d'une mission d'une journée
                // j'ajoute à la liste des missions
                if ($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsToDisplay[] = $mission;
                    $totalAmount += $mission->getRemuneration();
                    $totalHours += $mission->getHours();
                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                    for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTime = new \DateTime;

                        if ($i == 0) {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d")
                            );
                        } else {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i
                            );
                        }

                        $newMission->setBeginAt($dateTime);
                        $totalAmount += $newMission->getRemuneration();
                        $totalHours += $newMission->getHours();
                        $missionsToDisplay[] = $newMission;
                    }

                }


            }

            $contractDate = new \DateTime;
            $contractDate->setDate($mission->getBeginAt()->format("Y"), $mission->getBeginAt()->format("m"), 1);
            $bdcNumber = "B" . $contractDate->format("Ym") . $userId;
            $contractNumber = "C" . $contractDate->format("Ym") . $userId;

            $data = [
                'missions' => $missionsToDisplay,
                'teacher' => $user,
                'bdcNumber' => $bdcNumber,
                'contractNumber' => $contractNumber,
                'contractDate' => $contractDate,
                'totalAmount' => $totalAmount,
                'totalHours' => $totalHours
            ];
            $html = $this->renderView('contracts/template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            // GOOD WAY TO RENAME PDF
            return new Response(
                $dompdf->stream($this->params->get('nom_entreprise') . " - " . $bdcNumber, ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }


    // Méthode permettant de générer le contrat de prestations de service pour un client pour l'année en cours
    #[Route('/contracts/b2b/{clientId}/{year}', name: 'app_contracts_b2b_for_current_year')]
    public function generate_contrats_for_year(EntityManagerInterface $doctrine, int $clientId, int $year): Response
    {

        $client = $doctrine->getRepository(Clients::class)->find($clientId);
        $courses = $doctrine->getRepository(Mission::class)->findDifferentCoursesForClientAndYear($clientId, $year);
        $teachers = $doctrine->getRepository(Mission::class)->findDistinctTeachersForCustomers($clientId, $year);
        $teachersAndCourses = $doctrine->getRepository(Mission::class)->findDifferentTeachersForClientAndCourseAndYear($clientId, $year);

        $data = [
            // 'missions'  => $missionsForInvoice,
            // 'teacher' => $user,
            // 'invoiceNumber' => $invoiceNumber,
            // 'invoiceDate' => $invoiceDate,
            // 'invoiceDateEcheance' => $invoiceDateEcheance,
            // 'totalAmount' => round($totalAmount, 0),
            // 'client' => $client,
            // 'orderNumber' => $orderNumber
            'courses' => $courses,
            'teachers' => $teachers,
            'teachersAndCourses' => $teachersAndCourses,
            'client' => $client,
            'nextYear' => $year + 1,
            'year' => $year,
        ];

        $html = $this->renderView('contracts/year.html.twig', $data);
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response(
            $dompdf->stream($this->params->get('nom_entreprise') . " - contrat de prestations de formation - " . $client->getName() . " " . $client->getCommercialName() . " - " . $year . "_" . $year + 1),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );

    }

    #[Route('/contract/admin/download/{teacherId}/{month}/{year}', name: 'app_contracts_admin_download')]
    public function contracts_admin_download(
        Request $request,
        EntityManagerInterface $doctrine,
        int $teacherId,
        int $month,
        int $year
    ): Response {

        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {

            $missions = $doctrine->getRepository(Mission::class)->findMonthMissions($teacherId, $year, $month);

            $user = $doctrine->getRepository(User::class)->findBy(['id' => $teacherId])[0];

            $missionsToDisplay = [];
            $totalAmount = null;
            $totalHours = null;
            foreach ($missions as $mission) {

                // si il s'agit d'une mission d'une journée
                // j'ajoute à la liste des missions
                if ($mission->getBeginAt() == $mission->getEndAt()) {
                    $missionsToDisplay[] = $mission;
                    $totalAmount += $mission->getRemuneration();
                    $totalHours += $mission->getHours();
                } else {
                    // si il s'agit d'une mission sur plusieurs jours
                    // faut que je décortique la mission
                    $nbrOfDayForMission = ($mission->getEndAt()->format("d") - $mission->getBeginAt()->format("d")) + 1; // 5

                    for ($i = 0; $i < $nbrOfDayForMission; $i++) {
                        $newMission = clone $mission;
                        $dateTime = new \DateTime;

                        if ($i == 0) {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d")
                            );
                        } else {
                            $dateTime->setDate(
                                $mission->getBeginAt()->format("Y"),
                                $mission->getBeginAt()->format("m"),
                                $mission->getBeginAt()->format("d") + $i
                            );
                        }

                        $newMission->setBeginAt($dateTime);
                        $totalAmount += $newMission->getRemuneration();
                        $totalHours += $newMission->getHours();
                        $missionsToDisplay[] = $newMission;
                    }

                }


            }

            $contractDate = new \DateTime;
            $contractDate->setDate($mission->getBeginAt()->format("Y"), $mission->getBeginAt()->format("m"), 1);
            $bdcNumber = "B" . $contractDate->format("Ym") . $teacherId;
            $contractNumber = "C" . $contractDate->format("Ym") . $teacherId;

            $data = [
                'missions' => $missionsToDisplay,
                'teacher' => $user,
                'bdcNumber' => $bdcNumber,
                'contractNumber' => $contractNumber,
                'contractDate' => $contractDate,
                'totalAmount' => $totalAmount,
                'totalHours' => $totalHours
            ];
            $html = $this->renderView('contracts/template.html.twig', $data);
            $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response(
                $dompdf->stream($this->params->get('nom_entreprise') . " - " . $bdcNumber, ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );

        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

    }

    #[Route('/contract/upload/{year}/{month}/{userId}', name: 'app_contract_uploads', methods: ['POST'])]
    public function upload_contract(Request $request, EntityManagerInterface $doctrine, int $userId, int $month, int $year): Response
    {
        $user = $doctrine->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $file = $request->files->get('contractFile');

        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        // Valider le type de fichier
        if ($file->getMimeType() !== 'application/pdf') {
            return $this->json(['error' => 'Format de fichier invalide. Veuillez charger un PDF.'], Response::HTTP_BAD_REQUEST);
        }

        // Définir un nom unique pour le fichier
        $currentDate = (new \DateTime())->format('Y-m-d');
        $newFilename = $user->getLastName() . '_' . $user->getFirstName() . '_contrat_' . $year . '_' . $month . '_' . $currentDate . '.' . $file->guessExtension();

        // Créer le chemin d'accès en fonction de l'année et du mois
        $uploadDirectory = $this->getParameter('contracts_directory') . '/' . $year . '/' . sprintf('%02d', $month);

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Déplacer le fichier dans le dossier d'uploads
        try {
            $filePath = $uploadDirectory . '/' . $newFilename;
            $file->move($uploadDirectory, $newFilename);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to save file: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Mettre à jour la base de données
        $contract = new Contract();
        $contract->setUser($user);
        $contract->setDate(new \DateTime());
        $contract->setSigned(true);
        $contract->setContract($newFilename);

        $doctrine->persist($contract);
        $doctrine->flush();

        return $this->json(['success' => 'Contract uploaded successfully'], Response::HTTP_OK);
    }


}
