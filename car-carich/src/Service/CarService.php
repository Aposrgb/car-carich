<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\Country;
use App\Helper\DTO\Car\CarCreateRequestDTO;
use App\Helper\Enum\Type\Car as CarType;
use App\Helper\Exception\ApiException;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CarRepository          $carRepository,
    )
    {
    }


    public function create(CarCreateRequestDTO $carCreateRequestDTO): void
    {
        if ($this->carRepository->findOneBy(['name' => $carCreateRequestDTO->name])) {
            throw new ApiException('Car with this name already exists');
        }
        $country = $this->entityManager->getRepository(Country::class)->findOneBy(['name' => 'Китай']);
        $car = new Car();
        if(!empty($carCreateRequestDTO->mileageOneCharge)){
            $car->setMileageOneCharge($carCreateRequestDTO->mileageOneCharge);
        }
        if(!empty($carCreateRequestDTO->battery)){
            $car->setBattery($carCreateRequestDTO->battery);
        }
        if(!empty($carCreateRequestDTO->power) and $carCreateRequestDTO->power != '-'){
            $car->setPower($carCreateRequestDTO->power);
        }
        if(in_array($carCreateRequestDTO->type, CarType::getTypes())){
            $car->setTypeEngine($carCreateRequestDTO->type);
        } else {
            $car->setTypeEngine(CarType::PETROL->value);
        }
        $car
            ->setName($carCreateRequestDTO->name)
            ->setCountry($country)
            ->setYear($carCreateRequestDTO->year)
            ->setMileage($carCreateRequestDTO->mileage)
            ->setFullPrice($carCreateRequestDTO->price)
            ->setImages($carCreateRequestDTO->images);
        $this->entityManager->persist($car);
        $this->entityManager->flush();
    }
}