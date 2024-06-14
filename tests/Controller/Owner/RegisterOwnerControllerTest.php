<?php

namespace App\Tests\Controller\Owner;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterOwnerControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proprietaire/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Louer votre parking');
    }
}
