<?php

namespace App\Service;

use App\Entity\Car;
use App\Helper\Filter\FilterLkResponse;
use App\Repository\CarRepository;

final readonly class LkService
{
    public function __construct(
        private CarRepository $carRepository,
    ) {
    }

    public function createLkFilterCatalogue(): FilterLkResponse
    {
        $cars = $this->carRepository->findAll();
        $lkFilter = new FilterLkResponse();
        foreach ($cars as $car) {
            $this->filterPriceCar($car, $lkFilter);
            $this->filterYear($car->getYear(), $lkFilter);
        }
        return $lkFilter;
    }

    public function createLkFilterIndex(): FilterLkResponse
    {
        $cars = $this->carRepository->findAll();
        $lkFilter = new FilterLkResponse();
        [$yearsFilter, $stampFilter, $countryFilter, $modelFilter] = [[], [], [], []];
        foreach ($cars as $car) {
            $this->filterPriceCar($car, $lkFilter);
            if (!in_array($car->getYear(), $yearsFilter)) {
                $yearsFilter[] = $car->getYear();
            }
            if (($stamp = $car->getStamp()) && !empty($stamp->getName())) {
                if (!array_key_exists($stamp->getId(), $stampFilter)) {
                    $stampFilter[$stamp->getId()] = [
                        'id' => $stamp->getId(),
                        'name' => $stamp->getName(),
                        'count' => 1
                    ];
                } else {
                    $stampFilter[$stamp->getId()]['count']++;
                }
            }
            if (($model = $car->getModel()) && !empty($model->getName())) {
                if (!array_key_exists($model->getId(), $modelFilter)) {
                    $modelFilter[$model->getId()] = [
                        'id' => $model->getId(),
                        'name' => $model->getName(),
                    ];
                }
            }
            if (!empty($car->getCountry()?->getName())) {
                $countryFilter[$car->getCountry()->getName()] = [
                    'id' => $car->getCountry()->getId(),
                    'name' => $car->getCountry()->getName(),
                ];
            }
        }
        sort($yearsFilter);
        asort($stampFilter);
        asort($countryFilter);
        asort($modelFilter);
        return $lkFilter
            ->setModelFilter($modelFilter)
            ->setStampFilter($stampFilter)
            ->setCountryFilter($countryFilter)
            ->setYearsFilter($yearsFilter);
    }

    private function filterYear(?int $year, FilterLkResponse $lkFilter): void
    {
        if (!is_null($year)) {
            if ($lkFilter->getMaxYear() < $year) {
                $lkFilter->setMaxYear($year);
            }
            if ($lkFilter->getMinYear() == 0 or $lkFilter->getMinYear() > $year) {
                $lkFilter->setMinYear($year);
            }
        }
    }

    private function filterPriceCar(Car $car, FilterLkResponse $lkFilter): void
    {
        if (!is_null($car->getFullPrice())) {
            if ($lkFilter->getMaxPriceFilter() < $car->getFullPrice()) {
                $lkFilter->setMaxPriceFilter($car->getFullPrice());
            }

            if ($lkFilter->getMinPriceFilter() == 0 or $lkFilter->getMinPriceFilter() > $car->getFullPrice()) {
                $lkFilter->setMinPriceFilter($car->getFullPrice());
            }
        }
    }
}