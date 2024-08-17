<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Service\SnowDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocalisationVisualisationController extends AbstractController
{
    public const CURRENT_WINTER = 2024;

    public function __construct(private readonly SnowDataService $snowDataService)
    {
    }

    #[Route('/localisation/visualisation/{id}', name: 'app_localisation_visualisation')]
    public function index(Localisation $localisation, Request $request): Response
    {
        $winter = $request->query->getInt("winter", self::CURRENT_WINTER);

        $data = $this->snowDataService->findDataByWinterAndLocalisation($winter, $localisation);

        return $this->render('localisation_visualisation/index.html.twig', [
        'datas' => $data,
        'snowStatistics' => $this->snowDataService->getStatisticsData($data),
            'localisation' => $localisation,
            'winter' => $winter,
            'listWinters' => $this->snowDataService->getWinters()

        ]);
    }
}
