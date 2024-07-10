<?php

namespace App\Manager\Owner;

use App\Entity\Garage;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\CityRepository;
use App\Repository\GarageRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GarageManager
{
    public function __construct(private GarageRepository $garageRepository, private CityRepository $cityRepository,
                                private FileUploader $uploader, private EntityManagerInterface $manager)
    {
    }

    public function index(User $user): array
    {
        return $this->garageRepository->findBy(['owner' => $user], ['createdAt' => 'DESC']);
    }

    /**
     * @param Garage $garage
     * @param User $user
     * @param string $cityId
     * @param string|null $defaultImage
     * @return void
     * @throws \Exception
     */
    public function create(Garage  $garage, User $user, string $cityId, Form $form ,string $defaultImage = null): void
    {
        try {
            $city = $this->cityRepository->findOneBy(['id' => $cityId]);
            $garage->setCity($city);
            $garage->setOwner($user);
            $imagesFiles = $form->get("images")->getData();
            if ($imagesFiles) {
                $isFirst = true; // Variable de contrôle pour le premier élément
                foreach ($imagesFiles as $datum) {
                    /** @var UploadedFile $datum */
                    if ($datum) {
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

                        $this->manager->persist($image);
                    }
                }
            }
            $this->manager->persist($garage);
            $this->manager->flush();

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}