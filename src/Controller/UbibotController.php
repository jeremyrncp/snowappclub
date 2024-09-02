<?php

namespace App\Controller;
use App\Service\UbibotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ubibot')]
class UbibotController extends AbstractController
{
    public function __construct(private readonly UbibotService $ubibotService) {
    }

    #[Route('/api/push', name: 'app_ubibot_api_push', methods: ['POST'])]
    public function apiPush(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $fileName = date("H-i-s-Y-m-d");

        file_put_contents(__DIR__ . '/../../public/payloads/' . $fileName . '.json', $content);


        $decoded = json_decode($content, true);

        $serial = $decoded['serial'];

        $this->ubibotService->decodeAndInsert($serial, $decoded);

        return $this->json("success");
    }

    #[Route('/dashboard/{serial}', name: 'app_ubibot_dashboard', methods: ['GET'])]
    public function getDashboard(string $serial, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $day = $request->query->getInt("day", 3);

        $data = $this->ubibotService->getDataBySerialLast3daysOrderedByDateDESC($serial, $day);

        return $this->render('ubibot/dashboard.html.twig', [
            'datas' => $data,
            'firstData' => current($data),
            'station' => $this->ubibotService->getWeatherStationBySerial($serial)
        ]);
    }
}
