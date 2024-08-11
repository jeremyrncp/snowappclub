<?php

namespace App\Controller;
use App\Entity\Localisation;
use App\Repository\LocalisationRepository;
use App\Repository\SnowDataRepository;
use App\Service\SnowDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

#[Route('/api')]
class ApiController extends AbstractController
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

    protected function success(mixed $data, array $groups = []): JsonResponse
    {
        return $this->json($data, 200, [], $this->getContextWithGroups($groups));
    }


    protected function getContextWithGroups(array $groups = []): array
    {
        return (new ObjectNormalizerContextBuilder())
            ->withGroups($groups)
            ->toArray();
    }
}
