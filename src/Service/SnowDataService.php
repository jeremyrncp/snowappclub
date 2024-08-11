<?php

namespace App\Service;

use App\Entity\Localisation;
use App\Entity\SnowData;
use App\Repository\SnowDataRepository;

class SnowDataService
{
    public function __construct(
        private readonly SnowDataRepository $snowDataRepository
    )
    {
    }

    /**
     * get snow statistics
     *
     * @param array $snowDatas
     *
     * @return int[]
     */
    public function getStatisticsData(array $snowDatas)
    {
        $statistics = [
            'maxSnowFresh' => 0,
            'sumSnowFresh' => 0,
            'avgSnowFresh' => 0,
            'avgSnowTotal' => 0,
            'maxSnowTotal' => 0,
            'sumSnow' => 0,
        ];

        if (empty($snowDatas)) {
            return $statistics;
        }

        $tempSnowFresh = [];
        $tempSnowTotal = [];

        /** @var SnowData $snowData */
        foreach ($snowDatas as $snowData) {
            if ($snowData->getSnowFresh() > 0) {
                $tempSnowFresh[] = $snowData->getSnowFresh();
            }
            $tempSnowTotal[] = $snowData->getSnowTotal();

            if ($snowData->isSnow()) {
                $statistics['sumSnow'] ++;
            }
        }

        $statistics['maxSnowFresh'] = !empty($tempSnowFresh) ? max($tempSnowFresh) : 0;
        $statistics['maxSnowTotal'] = !empty($tempSnowTotal) ? max($tempSnowTotal) : 0;
        $statistics['sumSnowFresh'] = array_sum($tempSnowFresh);
        $statistics['avgSnowFresh'] = count($tempSnowFresh) > 0 ? round(array_sum($tempSnowFresh) / count($tempSnowFresh)) : 0;
        $statistics['avgSnowTotal'] = count($tempSnowTotal) > 0 ? round(array_sum($tempSnowTotal) / count($tempSnowTotal)) : 0;

        return $statistics;
    }

    public function findDataByWinterAndLocalisation(int $winter, Localisation $localisation): array
    {
        $startWinterDate = new \DateTime(($winter-1) . "-08-01");
        $endWinterDate = new \DateTime($winter. "-07-01");

        return $this->snowDataRepository->findBetweenDatesAndLocalisation($startWinterDate, $endWinterDate, $localisation);
    }
}
