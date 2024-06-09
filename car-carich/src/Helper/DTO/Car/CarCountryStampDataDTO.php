<?php

namespace App\Helper\DTO\Car;

class CarCountryStampDataDTO
{
    private array $cars;
    private array $countries;
    private array $stamps;

    public function __construct(
        array $cars,
        array $countries,
        array $stamps,
    )
    {
        $this->cars = $this->keysToLower($cars);
        $this->countries = $this->keysToLower($countries);
        $this->stamps = $this->keysToLower($stamps);
    }

    private function keysToLower(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[mb_strtolower($key)] = $value;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getCars(): array
    {
        return $this->cars;
    }

    /**
     * @param array $cars
     * @return CarCountryStampDataDTO
     */
    public function setCars(array $cars): CarCountryStampDataDTO
    {
        $this->cars = $cars;
        return $this;
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     * @return CarCountryStampDataDTO
     */
    public function setCountries(array $countries): CarCountryStampDataDTO
    {
        $this->countries = $countries;
        return $this;
    }

    /**
     * @return array
     */
    public function getStamps(): array
    {
        return $this->stamps;
    }

    /**
     * @param array $stamps
     * @return CarCountryStampDataDTO
     */
    public function setStamps(array $stamps): CarCountryStampDataDTO
    {
        $this->stamps = $stamps;
        return $this;
    }
}