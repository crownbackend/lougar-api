<?php

namespace App\Tests\Controller\Tenant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTenantControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/locataire/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Louer une place parking');
    }
}
