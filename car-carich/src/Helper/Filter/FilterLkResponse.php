<?php

namespace App\Helper\Filter;

use App\Helper\Enum\Type\CarType;

class FilterLkResponse
{
    public function __construct(
        private int $maxPriceFilter = 0,
        private int $minPriceFilter = 0,
        private array $yearsFilter = [],
        private array $carTypes = [],
        private array $stampFilter = [],
        private array $countryFilter = [],
        private array $modelFilter = [],
        private int $maxYear = 0,
        private int $minYear = 0,
    )
    {
        $this->carTypes = CarType::getNames();
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

    public function getCountryFilter(): array
    {
        return $this->countryFilter;
    }

    public function setCountryFilter(array $countryFilter): FilterLkResponse
    {
        $this->countryFilter = $countryFilter;
        return $this;
    }

    public function getModelFilter(): array
    {
        return $this->modelFilter;
    }

    public function setModelFilter(array $modelFilter): FilterLkResponse
    {
        $this->modelFilter = $modelFilter;
        return $this;
    }

    public function getMaxYear(): int
    {
        return $this->maxYear;
    }

    public function setMaxYear(int $maxYear): FilterLkResponse
    {
        $this->maxYear = $maxYear;
        return $this;
    }

    public function getMinYear(): int
    {
        return $this->minYear;
    }

    public function setMinYear(int $minYear): FilterLkResponse
    {
        $this->minYear = $minYear;
        return $this;
    }

}