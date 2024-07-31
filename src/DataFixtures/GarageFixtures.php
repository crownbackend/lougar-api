<?php

namespace App\DataFixtures;

use App\Entity\Garage;
use App\Entity\Image;
use App\Repository\CityRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GarageFixtures extends Fixture
{
    public function __construct(private CityRepository $cityRepository, private UserRepository $userRepository)
    {
    }

    public function load(ObjectManager $manager)
    {
        ini_set('memory_limit', '-1');
        $faker = \Faker\Factory::create('fr_FR');

        $user1 = $this->userRepository->findOneBy(['id' => '01910a97-c124-7036-940b-9e0b572c8b4c']);
        $user2 = $this->userRepository->findOneBy(['id' => '01910a9c-71d1-73ce-928e-7621a5738753']);

        $cites = $this->cityRepository->findByName('par');
        foreach ($cites as $cite) {
            for ($i = 0; $i < 1; $i++) {
                $garage = new Garage();
                $garage->setCity($cite);
                $garage->setName($faker->word);
                $garage->setDescription($faker->text(1500));
                $garage->setAddress($faker->address);
                $garage->setPricePerHour($faker->randomFloat(2, 10, 5));
                $garage->setPricePerDay($faker->randomFloat(2, 10, 50));
                $garage->setOwner($user1);
                $image = new Image();
                $image->setName("clothes-3987460-1920-66906ed2e4c22.jpg");
                $image->setGarage($garage);
                $image->setPrincipal(true);
                $manager->persist($image);
                $manager->persist($garage);
            }

            for ($i = 0; $i < 1; $i++) {
                $garage1 = new Garage();
                $garage1->setCity($cite);
                $garage1->setName($faker->word);
                $garage1->setDescription($faker->text(2000));
                $garage1->setAddress($faker->address);
                $garage1->setPricePerHour($faker->randomFloat(2, 10, 5));
                $garage1->setPricePerDay($faker->randomFloat(2, 10, 50));
                $garage1->setOwner($user2);
                $image1 = new Image();
                $image1->setName("dresses-53319-1920-66906ed2e4eb2.jpg");
                $image1->setGarage($garage1);
                $image1->setPrincipal(true);
                $manager->persist($image1);
                $manager->persist($garage1);
            }
        }
        $manager->flush();
    }
}