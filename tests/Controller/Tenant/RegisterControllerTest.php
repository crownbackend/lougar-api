<?php

namespace App\Tests\Controller\Tenant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/locataire//inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Inscription');
    }
}
