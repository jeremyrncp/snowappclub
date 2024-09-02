<?php

namespace App\Service;

use App\Entity\WeatherData;
use App\Repository\WeatherDataRepository;
use App\Repository\WeatherStationRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Twig\Environment;

class UbibotService
{
    public function __construct(
        private readonly WeatherStationRepository $weatherStationRepository,
        private readonly WeatherDataRepository $weatherDataRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Environment $twig
    ) {
    }

    /**
     * Get static data
     *
     * @param string $serial
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getStatICLastDataBySerial(string $serial): string
    {
        $weatherStation = $this->weatherStationRepository->findOneBy(['serial' => $serial]);

        if (is_null($weatherStation)) {
            throw new NotFoundHttpException(sprintf("Weather station %s was not found", $serial));
        }

        $lastWeatherData = $this->weatherDataRepository->findOneBy(['station' => $weatherStation], ['createdAt' => 'DESC']);

        return $this->twig->render('static/static.txt.twig', [
            'createdAt' => $lastWeatherData->getCreatedAt(),
            'tOut' => $lastWeatherData->getTout(),
            'rhOut' => $lastWeatherData->getRhout(),
            'station' => $weatherStation
        ]);
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
