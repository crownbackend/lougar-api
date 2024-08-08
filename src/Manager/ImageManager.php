<?php

namespace App\Manager;

use App\Entity\Garage;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

class ImageManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function delete(Image $image): Garage
    {
        $garage = $image->getGarage();
        $image->setDeletedAt(new \DateTimeImmutable());
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $garage;
    }

    public function setPrincipal(Image $image): Garage
    {
        $garage = $image->getGarage();
        foreach ($garage->getImages() as $datum) {
            $datum->setUpdatedAt(new \DateTimeImmutable());
            $datum->setPrincipal(false);
            $this->entityManager->persist($datum);
        }
        $this->entityManager->flush();
        $image->setPrincipal(true);
        $this->entityManager->persist($image);
        $this->entityManager->flush();
        return $garage;
    }
}