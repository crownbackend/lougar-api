<?php

namespace App\Serializer\Normalizer;

use App\Entity\Reservation;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReservationNormalizer implements NormalizerInterface
{
    /**
     * @param Reservation $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $startAt = null;
        $endAt = null;
        $garage = [
            'id' => $object->getGarage()->getId(),
            'name' => $object->getGarage()->getName(),
        ];
        if($object->getInfo()['type'] === 'day') {
            $startAt = $object->getStartAt()->format('Y-m-d');
            $endAt = $object->getEndAt()->format('Y-m-d');
        } else if($object->getInfo()['type'] === 'hour') {
            $startAt = $object->getStartAt()->format('Y-m-d H:i');
            $endAt = $object->getEndAt()->format('Y-m-d H:i');
        }

        return [
            'id' => $object->getId(),
            'status' => $object->getStatus(),
            'startAt' => $startAt,
            'endAt' => $endAt,
            "garage" => $garage,
            'info' => $object->getInfo(),
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Reservation;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Reservation::class => true];
    }
}
