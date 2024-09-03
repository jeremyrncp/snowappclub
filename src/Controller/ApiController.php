<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class ApiController extends AbstractController
{

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

    protected function convertJsonToCSV($jsonDecoded, $csvFile)
    {
        $fp = fopen($csvFile, 'w');
        fputcsv($fp, array_keys($jsonDecoded[0]));
        for ($i = 0; $i < count($jsonDecoded); $i ++) {
            fputcsv($fp, array_values($jsonDecoded[$i]));
        }
        fclose($fp);
        return;
    }
}
