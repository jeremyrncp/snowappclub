<?php

namespace App\Controller;
use App\Entity\Localisation;
use App\Repository\LocalisationRepository;
use App\Repository\SnowDataRepository;
use App\Service\SnowDataService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiSnowController extends ApiController
{
    public function __construct(
        private readonly LocalisationRepository $localisationRepository,
        private readonly SnowDataRepository $snowDataRepository,
        private readonly SnowDataService $snowDataService
    ) {
    }

    #[Route('/bylocalisationandwinter/{id}/{winter}', name: 'app_api_snow_data_by_localisation_and_winter', methods: ['GET'])]
    public function getSnowDatasbyLocalisationAndWinter(Localisation $localisation, int $winter): JsonResponse
    {
        return $this->success($this->snowDataService->findDataByWinterAndLocalisation($winter, $localisation), ['snowdata:public']);
    }

    #[Route('/bylocalisationandwinter/{id}/{winter}/csv', name: 'app_api_snow_data_csv_by_localisation_and_winter', methods: ['GET'])]
    public function getSnowDatasCSVbyLocalisationAndWinter(Localisation $localisation, int $winter): BinaryFileResponse
    {
        $data = $this->success($this->snowDataService->findDataByWinterAndLocalisation($winter, $localisation), ['snowdata:apicsv']);
        $jsonDecoded = json_decode($data->getContent(), true);

        $fileName = urlencode(strtolower($localisation->getName()));
        $this->convertJsonToCSV($jsonDecoded, __DIR__. '/../../public/csv/'. $fileName . '.csv');

        return new BinaryFileResponse(__DIR__. '/../../public/csv/'. $fileName . '.csv', 200);
    }

    #[Route('/snowdatas', name: 'app_api_snow_data', methods: ['GET'])]
    public function getSnowDatas(): JsonResponse
    {
        return $this->success($this->snowDataRepository->findAll(), ['snowdata:public']);
    }

    #[Route('/localisations', name: 'app_api_localisations', methods: ['GET'])]
    public function getLocalisations(): JsonResponse
    {
        return $this->success($this->localisationRepository->findAll(), ['localisation:public']);
    }
}
