<?php

namespace App\Helper\DTO\Car;

final readonly class CarCreateRequestDTO
{
    public function __construct(
        public ?string $mileageOneCharge = null,
        public ?string $power = null,
        public ?string $name = null,
        public ?string $engine = null,
        public ?int    $type = null,
        public ?int    $year = null,
        public ?string $battery = null,
        public ?int    $mileage = null,
        public ?int    $price = null,
        /** @var string[] $images */
        public array   $images = [],
    )
    {
    }

}