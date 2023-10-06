<?php

namespace App\Tests;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $container = static::getContainer();

        $contact = new Contact();
        $contact->setCurrentJob("Développeur");
        $contact->setEmail("sam@mail.com");
        $contact->setIsRead(false);
        $contact->setLastName("Habbani");
        $contact->setFirstName("Samih");
        $contact->setMessage("mon msg");
        $contact->setObject("Je veux devenir formateur");
        $contact->setPhone("0123456789");

        $errors = $container->get('validator')->validate($contact);
        $this->assertCount(0, $errors);
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
