<?php

namespace App\Controller;

use App\Service\UbibotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StaticController extends AbstractController
{
    public function __construct(private readonly UbibotService $ubibotService)
    {

    }

    #[Route('/static/lemonastiersurgazeille', name: 'app_static_le_monastier_sur_gazeille')]
    public function index(): Response
    {
        $file = file_get_contents("https://www.meteodata.fr/data/statIC/4557d6b1-cccd-4f65-9c2d-e0172b7d7e28");

        $explode = explode(PHP_EOL, $file);

        $tempData = [];

        foreach ($explode as $item) {
            if (substr($item, 0, 1) !== '#') {
                $explodeEgal = explode('=', $item);


                if (count($explodeEgal) > 1) {
                    $tempData[$explodeEgal[0]] = $explodeEgal[1];
                }
            }
        }

        $tempData["temperature"] = $tempData["temperature_moy"];
        $tempData["humidite"] = $tempData["humidite_moy"];

        $str = "";

        foreach ($tempData as $key => $value) {
            $str .= $key . "=" . $value . PHP_EOL;
        }

        file_put_contents(__DIR__ . '/../../public/csv/lemonastier.txt', $str);

        return new BinaryFileResponse(__DIR__ . '/../../public/csv/lemonastier.txt');
    }

    #[Route('/static/ubibot/{serial}', name: 'app_static_ubibot')]
    public function staticUbibot(string $serial): Response
    {
        file_put_contents(__DIR__ . '/../../public/csv/'.$serial.'.txt', $this->ubibotService->getStatICLastDataBySerial($serial));

        return new BinaryFileResponse(__DIR__ . '/../../public/csv/'.$serial.'.txt');
    }
}
