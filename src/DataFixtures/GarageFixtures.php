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

        $user1 = $this->userRepository->findOneBy(['id' => '018fd5ee-7fce-7120-a11b-4d910378715b']);
        $user2 = $this->userRepository->findOneBy(['id' => '01901288-944f-75d9-b32d-75d4746341ab']);
        $user3 = $this->userRepository->findOneBy(['id' => '0190a414-89a5-779f-99d2-172de1ed624a']);

        $cites = $this->cityRepository->findByName('par');
        foreach ($cites as $cite) {
            for ($i = 0; $i < 5; $i++) {
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

            for ($i = 0; $i < 5; $i++) {
                $garage1 = new Garage();
                $garage1->setCity($cite);
                $garage1->setName($faker->word);
                $garage1->setDescription($faker->text(2899));
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

            for ($i = 0; $i < 5; $i++) {
                $garage2 = new Garage();
                $garage2->setCity($cite);
                $garage2->setName($faker->word);
                $garage2->setDescription($faker->text(1400));
                $garage2->setAddress($faker->address);
                $garage2->setPricePerHour($faker->randomFloat(2, 10, 5));
                $garage2->setPricePerDay($faker->randomFloat(2, 10, 50));
                $garage2->setOwner($user3);
                $image2 = new Image();
                $image2->setName("fashion-4132576-1920-66906ed2e5002.jpg");
                $image2->setGarage($garage2);
                $image2->setPrincipal(true);
                $manager->persist($image2);
                $manager->persist($garage2);
            }
        }
        $manager->flush();
    }
}