<?php

namespace App\Controller;
use App\Service\UbibotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


}
