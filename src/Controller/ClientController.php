<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients_list')]
    public function index(ClientsRepository $clientsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $clients = $clientsRepository->findAll();

        $clients = $paginator->paginate(
            $clients, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/clients/{id}', name: 'client_detail', methods: ['GET'])]
    public function detail(Clients $client): JsonResponse
    {
        return $this->json([
            'id' => $client->getId(),
            'name' => $client->getName(),
            'address' => $client->getAddress(),
            'city' => $client->getCity(),
            'postalCode' => $client->getPostalCode(),
            'personInCharge' => $client->getPersonInCharge(),
            'phonePersonInCharge' => $client->getPhonePersonInCharge(),
            'phoneRepresentative' => $client->getPhoneRepresentative(),
            'backgroundColor' => $client->getBackgroundColor(),
            'siret' => $client->getSiret(),
            'commercialName' => $client->getCommercialName(),
            'representative' => $client->getRepresentative(),
            'nbrAgrement' => $client->getNbrAgrement(),
            'naf' => $client->getNaf(),
            'legalForm' => $client->getLegalForm(),
            'socialCapital' => $client->getSocialCapital(),
            'missionAddress' => $client->getMissionAddress(),
            'missionClient' => $client->getMissionClient(),
            'missionPostalCode' => $client->getMissionPostalCode(),
            'missionCity' => $client->getMissionCity(),
            'emailPersonInCharge' => $client->getEmailPersonInCharge(),
            'emailRepresentative' => $client->getEmailRepresentative(),
            // Ajoutez ici d'autres informations si nécessaire
        ]);
    }

    #[Route('/client-details/{id}', name: 'client_details', methods: ['GET'])]
    public function clientDetails(int $id, ClientsRepository $clientRepository): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return new JsonResponse(['error' => 'Client not found.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $client->getId(),
            'name' => $client->getName(),
            'commercialName' => $client->getCommercialName(),
            'city' => $client->getCity(),
            'personInCharge' => $client->getPersonInCharge(),
            'emailPersonInCharge' => $client->getEmailPersonInCharge(),
            'emailRepresentative' => $client->getEmailRepresentative(),
        ]);
    }


}
