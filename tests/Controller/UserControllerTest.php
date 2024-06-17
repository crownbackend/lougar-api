<?php

declare(strict_types=1);

namespace Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
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
        $this->client->request('GET', '/utilisateurs/profil');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Information compte');
    }
}
