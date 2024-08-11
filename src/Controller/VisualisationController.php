<?php

namespace App\Controller;

use App\Form\VisualisationType;
use App\Service\SnowDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VisualisationController extends AbstractController
{
    public function __construct(private readonly SnowDataService $snowDataService)
    {
    }

    #[Route('/visualisation', name: 'app_visualisation')]
    public function index(Request $request): Response
    {
        $data = [];

        $visualisationForm = $this->createForm(VisualisationType::class);
        $visualisationForm->handleRequest($request);

        if ($visualisationForm->isSubmitted() && $visualisationForm->isValid()) {
            $data = $this->snowDataService->findDataByWinterAndLocalisation($visualisationForm->getData()['winter'], $visualisationForm->getData()['localisation']);
        }

        return $this->render('visualisation/index.html.twig', [
            'form' => $visualisationForm->createView(),
            'datas' => $data,
            'snowStatistics' => $this->snowDataService->getStatisticsData($data)
        ]);
    }
}
