<?php

namespace App\Tests\Controller\Tenant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTenantControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRegister(): void
    {
        $this->client->request('GET', '/locataire/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Louer une place parking');
    }
//    public function testDashboard(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/locataire/tableau-de-bord');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h4', 'Tableau de bord');
//    }
}
