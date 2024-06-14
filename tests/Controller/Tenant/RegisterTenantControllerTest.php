<?php

namespace App\Tests\Controller\Tenant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegisterTenantControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testRegister(): void
    {
        $this->client->request('GET', '/locataire/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Louer une place parking');
    }

    public function testProfile(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@admin.com']);
        if (!$user) {
            throw new NotFoundHttpException('User not found in the database.');
        }

        // Simuler la connexion
        $this->client->loginUser($user);

        // Accéder à la page sécurisée
        $this->client->request('GET', '/locataire/profil');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Information compte');
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
