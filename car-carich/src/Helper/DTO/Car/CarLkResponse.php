<?php

namespace App\Helper\DTO\Car;

use App\Entity\Car;
use App\Helper\Enum\Type\CarType as EnumCarType;
use App\Helper\Inteface\DTOInterface;

final readonly class CarLkResponse implements DTOInterface
{
    public ?int $id;
    public ?string $name;
    public ?string $typeEngine;
    public ?string $country;
    public ?string $weight;
    public ?string $size;
    public ?int $year;
    public ?string $battery;
    public ?int $mileage;
    public ?int $fullPrice;
    public ?int $standardPrice;
    public ?int $type;
    public ?string $stamp;
    public ?array $images;
    public ?string $power;
    public ?string $stampImg;
    public ?string $volume;
    public ?string $mileageOneCharge;

    public function __construct(Car $car)
    {
        $this->id = $car->getId();
        $this->name = $car->getName();
        $this->typeEngine = EnumCarType::tryFrom($car->getTypeEngine())?->getName();
        $this->type = $car->getTypeEngine();
        $this->country = $car->getCountry()?->getName();
        $this->weight = $car->getWeight();
        $this->size = $car->getSize();
        $this->year = $car->getYear();
        $this->battery = $car->getBattery();
        $this->mileage = $car->getMileage();
        $this->fullPrice = $car->getFullPrice();
        $this->standardPrice = $car->getStandardPrice();
        $this->stamp = $car->getStamp()?->getName();
        $this->images = $car->getImages();
        $this->power = $car->getPower();
        $this->stampImg = $car->getStamp()?->getIcon();
        $this->volume = $car->getVolume();
        $this->mileageOneCharge = $car->getMileageOneCharge();
    }
}