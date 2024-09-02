<?php

namespace App\Service;

use App\Entity\WeatherData;
use App\Repository\WeatherStationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UbibotService
{
    public function __construct(private readonly WeatherStationRepository $weatherStationRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Decode ubibot data and insert in data base
     *
     * @param string $serial
     * @param array  $data
     *
     * @return WeatherData
     * @throws \Exception
     */
    public function decodeAndInsert(string $serial, array $data): WeatherData
    {
        $weatherStation = $this->weatherStationRepository->findOneBy(['serial' => $serial]);

        if (is_null($weatherStation)) {
            throw new NotFoundHttpException(sprintf("Weather station %s was not found", $serial));
        }

        foreach ($data['feeds'] as $feed) {
            $createdAt = new \DateTime($feed['created_at']);
            if (array_key_exists("field1", $feed)) {
                $tInt = $feed['field1'];
            }

            if (array_key_exists("field2", $feed)) {
                $rhInt = $feed['field2'];
            }

            if (array_key_exists("field9", $feed)) {
                $tOut = $feed['field9'];
            }

            if (array_key_exists("field10", $feed)) {
                $rhOut = $feed['field10'];
            }
        }

        if (isset($createdAt) && isset($tInt) && isset($rhInt) && isset($tOut) && isset($rhOut)) {
            $weatherData = new WeatherData();
            $weatherData->setStation($weatherStation)
                        ->setCreatedAt($createdAt)
                        ->setTint($tInt)
                        ->setRhint($rhInt)
                        ->setTout($tOut)
                        ->setRhout($rhOut);

            $this->entityManager->persist($weatherData);
            $this->entityManager->flush();

            return $weatherData;
        }

        throw new UnprocessableEntityHttpException("Data not valid");
    }
}
