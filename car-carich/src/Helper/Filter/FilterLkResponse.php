<?php

namespace App\Helper\Filter;

use App\Helper\Enum\Type\Car;

class FilterLkResponse
{
    public function __construct(
        private int $maxPriceFilter = 0,
        private int $minPriceFilter = 0,
        private array $yearsFilter = [],
        private int $maxMileAge = 0,
        private int $minMileAge = -1,
        private array $carTypes = [],
        private array $stampFilter = [],
    )
    {
        $this->carTypes = Car::getNames();
    }

    public function getMaxPriceFilter(): int
    {
        return $this->maxPriceFilter;
    }

    public function setMaxPriceFilter(int $maxPriceFilter): FilterLkResponse
    {
        $this->maxPriceFilter = $maxPriceFilter;
        return $this;
    }

    public function getMinPriceFilter(): int
    {
        return $this->minPriceFilter;
    }

    public function setMinPriceFilter(int $minPriceFilter): FilterLkResponse
    {
        $this->minPriceFilter = $minPriceFilter;
        return $this;
    }

    public function getYearsFilter(): array
    {
        return $this->yearsFilter;
    }

    public function setYearsFilter(array $yearsFilter): FilterLkResponse
    {
        $this->yearsFilter = $yearsFilter;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxMileAge(): int
    {
        return $this->maxMileAge;
    }

    /**
     * @param int $maxMileAge
     * @return FilterLkResponse
     */
    public function setMaxMileAge(int $maxMileAge): FilterLkResponse
    {
        $this->maxMileAge = $maxMileAge;
        return $this;
    }

    public function getMinMileAge(): int
    {
        return $this->minMileAge;
    }

    public function setMinMileAge(int $minMileAge): FilterLkResponse
    {
        $this->minMileAge = $minMileAge;
        return $this;
    }

    public function getCarTypes(): array
    {
        return $this->carTypes;
    }

    public function setCarTypes(array $carTypes): FilterLkResponse
    {
        $this->carTypes = $carTypes;
        return $this;
    }

    public function getStampFilter(): array
    {
        return $this->stampFilter;
    }

    public function setStampFilter(array $stampFilter): FilterLkResponse
    {
        $this->stampFilter = $stampFilter;
        return $this;
    }

}