<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@admin.com');
        $password = $this->passwordHasher->hashPassword($user, 'test');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstName('admin');
        $user->setLastName('admin');
        $user->setBirthday(new \DateTimeImmutable(''));
        $user->setPhone('0123456789');
        $manager->persist($user);
        $manager->flush();
    }
}
