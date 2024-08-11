<?php

namespace App\Manager\Owner;

use App\Entity\Garage;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\CityRepository;
use App\Repository\GarageRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class GarageManager
{
    public function __construct(private readonly GarageRepository $garageRepository, private readonly CityRepository $cityRepository,
                                private readonly FileUploader $uploader, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function count(User $user): int
    {
        return $this->garageRepository->findByCount($user);
    }

    public function index(User $user): Query
    {
        return $this->garageRepository->findByOwner($user);
    }

    public function show(string $id): Garage
    {
        return $this->garageRepository->findById($id);
    }

    /**
     * @param Garage $garage
     * @param User $user
     * @param string $cityId
     * @param string|null $defaultImage
     * @return void
     * @throws \Exception
     */
    public function create(Garage $garage, User $user, string $cityId, Form $form ,string $defaultImage = null): void
    {
        try {
            $city = $this->cityRepository->findOneBy(['id' => $cityId]);
            $garage->setCity($city);
            $garage->setOwner($user);
            $imagesFiles = $form->get("images")->getData();
            if ($imagesFiles) {
                $isFirst = true;
                foreach ($imagesFiles as $datum) {
                    /** @var UploadedFile $datum */
                    $imageFileName = $this->uploader->upload($datum);
                    $image = new Image();
                    $image->setName($imageFileName);
                    $image->setGarage($garage);
                    if ($defaultImage !== null) {
                        if ($defaultImage === $datum->getClientOriginalName()) {
                            $image->setPrincipal(true);
                        } else {
                            $image->setPrincipal(false);
                        }
                    } else {
                        if ($isFirst) {
                            $image->setPrincipal(true);
                            $isFirst = false;
                        } else {
                            $image->setPrincipal(false);
                        }
                    }

                    $this->entityManager->persist($image);
                }
            }
            $this->entityManager->persist($garage);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function edit(Garage $garage, Form $form, string $cityId = null ,string $defaultImage = null): void
    {
        if($cityId) {
            $city = $this->cityRepository->findOneBy(['id' => $cityId]);
            $garage->setCity($city);
        }

        $garage->setUpdatedAt(new \DateTimeImmutable());

        $imagesFiles = $form->get("images")->getData();
        if ($imagesFiles) {
            foreach ($imagesFiles as $datum) {
                /** @var UploadedFile $datum */
                $imageFileName = $this->uploader->upload($datum);
                $image = new Image();
                $image->setName($imageFileName);
                $image->setGarage($garage);
                if($defaultImage) {
                    $this->setImagesFalse($garage);
                    if ($defaultImage === $datum->getClientOriginalName()) {
                        $image->setPrincipal(true);
                    } else {
                        $image->setPrincipal(false);
                    }
                } else {
                    $image->setPrincipal(false);
                }
                $this->entityManager->persist($image);
            }
        }
        $this->entityManager->persist($garage);
        $this->entityManager->flush();
    }

    public function delete(Garage $garage): void
    {
        $garage->setDeletedAt(new \DateTimeImmutable());
        foreach ($garage->getImages() as $image) {
            $image->setDeletedAt(new \DateTimeImmutable());
            $this->entityManager->persist($image);
        }
        $this->entityManager->persist($garage);
        $this->entityManager->flush();
    }

    private function setImagesFalse(Garage $garage): void
    {
        $images = $garage->getImages();
        foreach ($images as $image) {
            $image->setPrincipal(false);
            $this->entityManager->persist($image);
            $image->setUpdatedAt(new \DateTimeImmutable());
        }
        $this->entityManager->flush();
    }
}