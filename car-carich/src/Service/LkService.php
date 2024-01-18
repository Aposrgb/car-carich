<?php

namespace App\Service;

use App\Entity\Car;
use App\Helper\Filter\FilterLkResponse;
use App\Repository\CarRepository;

final readonly class LkService
{
    public function __construct(
        private CarRepository $carRepository,
    )
    {
    }

    public function createLkFilterCatalogue(): FilterLkResponse
    {
        $cars = $this->carRepository->findAll();
        $lkFilter = new FilterLkResponse();
        foreach ($cars as $car) {
            $this->filterPriceCar($car, $lkFilter);
            $this->filterMileAge($car->getMileage(), $lkFilter);
        }
        if ($lkFilter->getMinMileAge() < 0) {
            $lkFilter->setMinMileAge(0);
        }
        return $lkFilter;
    }

    public function createLkFilterIndex(): FilterLkResponse
    {
        $cars = $this->carRepository->findAll();
        $lkFilter = new FilterLkResponse();
        [$yearsFilter, $stampFilter] = [[], []];
        foreach ($cars as $car) {
            $this->filterPriceCar($car, $lkFilter);
            if (!in_array($car->getYear(), $yearsFilter)) {
                $yearsFilter[] = $car->getYear();
            }
            if ($car->getStamp() and !in_array($car->getStamp()->getName(), $stampFilter)) {
                $stampFilter[$car->getStamp()->getId()] = $car->getStamp()->getName();
            }
        }
        sort($yearsFilter);
        asort($stampFilter);
        return $lkFilter
            ->setStampFilter($stampFilter)
            ->setYearsFilter($yearsFilter);
    }

    private function filterMileAge(?int $mileAge, FilterLkResponse $lkFilter): void
    {
        if (!is_null($mileAge)) {
            if ($lkFilter->getMaxMileAge() < $mileAge) {
                $lkFilter->setMaxMileAge($mileAge);
            }
            if ($lkFilter->getMinMileAge() == -1 or $lkFilter->getMinMileAge() > $mileAge) {
                $lkFilter->setMinMileAge($mileAge);
            }
        }
    }

    private function filterPriceCar(Car $car, FilterLkResponse $lkFilter): void
    {
        if (!is_null($car->getFullPrice()) and $lkFilter->getMaxPriceFilter() < $car->getFullPrice()) {
            $lkFilter->setMaxPriceFilter($car->getFullPrice());
        }
        if (!is_null($car->getStandardPrice()) and
            ($lkFilter->getMinPriceFilter() == 0 or $lkFilter->getMinPriceFilter() > $car->getStandardPrice())
        ) {
            $lkFilter->setMinPriceFilter($car->getStandardPrice());
        }
    }
}