<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ubibot')]
class UbibotController extends AbstractController
{
    public function __construct() {
    }

    #[Route('/api/push', name: 'app_ubibot_api_push', methods: ['POST'])]
    public function apiPush(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $fileName = time();

        file_put_contents(__DIR__ . '/../../public/payloads/' . $fileName . '.json', $content);

        return $this->json("success");
    }
}
