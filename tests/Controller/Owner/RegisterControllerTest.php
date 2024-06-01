<?php

namespace App\Tests\Controller\Owner;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proprietaire/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Louer votre parking');
    }
}
