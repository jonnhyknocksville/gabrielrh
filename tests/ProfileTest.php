<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileTest extends WebTestCase
{
    public function testSomething(): void
    {
        // JE TESTE QUE LA RÉPONSE DE MON CONTROLLER EST LA BONNE
        // ICI JE NE SUIS PAS AUTHORISÉ À AVOIR ACCÈS À LA PAGE PROFIL
        // CAR JE N'AI PAS LES DROITS
        $client = static::createClient();
        $client = $client->request('GET', '/profile');

        $this->assertResponseStatusCodeSame(Response::HTTP_AUTHORIZED);
    }
}
