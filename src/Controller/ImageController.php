<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Image;
use App\Manager\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/image', name: "image_")]
class ImageController extends AbstractController
{
    public function __construct(private ImageManager $imageManager)
    {
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET','DELETE'])]
    public function delete(Image $image): Response
    {
        $garage = $this->imageManager->delete($image);
        return $this->render('owner/garage/images.html.twig', [
            'garage' => $garage,
        ]);
    }

    #[Route('/{id}/principal', name: 'principal', methods: ['POST'])]
    public function setPrincipal(Image $image): Response
    {
        $garage = $this->imageManager->setPrincipal($image);
        return $this->render('owner/garage/images.html.twig', [
            'garage' => $garage,
        ]);
    }
}
