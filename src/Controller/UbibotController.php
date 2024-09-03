<?php

namespace App\Controller;
use App\Service\UbibotService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ubibot')]
class UbibotController extends ApiController
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

        $dataDESC = $this->ubibotService->getDataBySerialLast3daysOrderedByDateDESC($serial, $day);
        $dataASC = $this->ubibotService->getDataBySerialLast3daysOrderedByDateASC($serial, $day);

        return $this->render('ubibot/dashboard.html.twig', [
            'datasDESC' => $dataDESC,
            'datasASC' => $dataASC,
            'firstData' => current($dataDESC),
            'station' => $this->ubibotService->getWeatherStationBySerial($serial)
        ]);
    }

    #[Route('/api/{serial}', name: 'app_ubibot_api', methods: ['GET'])]
    public function getApi(string $serial, Request $request): JsonResponse
    {
        $day = $request->query->getInt("day", 3);

        $dataASC = $this->ubibotService->getDataBySerialLast3daysOrderedByDateASC($serial, $day);

        return $this->success($dataASC, ['weatherdata:public']);
    }

    #[Route('/api/{serial}/csv', name: 'app_ubibot_api_csv', methods: ['GET'])]
    public function getApiCsv(string $serial, Request $request): BinaryFileResponse
    {
        $day = $request->query->getInt("day", 3);

        $dataASC = $this->ubibotService->getDataBySerialLast3daysOrderedByDateASC($serial, $day);

        $data = $this->success($dataASC, ['weatherdata:apicsv']);
        $jsonDecoded = json_decode($data->getContent(), true);

        $fileName = urlencode(strtolower($serial));
        $this->convertJsonToCSV($jsonDecoded, __DIR__. '/../../public/csv/'. $fileName . '.csv');

        return new BinaryFileResponse(__DIR__. '/../../public/csv/'. $fileName . '.csv', 200);
    }
}
