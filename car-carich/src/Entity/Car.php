<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $typeEngine = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?Country $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $size = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $battery = null;

    #[ORM\Column(nullable: true)]
    private ?int $mileage = null;

    #[ORM\Column]
    private ?int $fullPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $standardPrice = null;

    #[ORM\Column(type: Types::JSON)]
    private array $images = [];

    #[ORM\Column(nullable: true)]
    private ?bool $isPopular = null;

    #[ORM\Column(nullable: true)]
    private ?string $power;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?Stamp $stamp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $volume;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mileageOneCharge;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTypeEngine(): ?int
    {
        return $this->typeEngine;
    }

    public function setTypeEngine(int $typeEngine): static
    {
        $this->typeEngine = $typeEngine;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getBattery(): ?string
    {
        return $this->battery;
    }

    public function setBattery(?string $battery): static
    {
        $this->battery = $battery;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(?int $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getFullPrice(): ?int
    {
        return $this->fullPrice;
    }

    public function setFullPrice(int $fullPrice): static
    {
        $this->fullPrice = $fullPrice;

        return $this;
    }

    public function getStandardPrice(): ?int
    {
        return $this->standardPrice;
    }

    public function setStandardPrice(?int $standardPrice): static
    {
        $this->standardPrice = $standardPrice;

        return $this;
    }

    public function getImages(): array
    {
        return array_values($this->images);
    }

    public function setImages(array $images): static
    {
        $this->images = array_values($images);

        return $this;
    }

    public function getIsPopular(): ?bool
    {
        return $this->isPopular;
    }

    public function setIsPopular(?bool $isPopular): Car
    {
        $this->isPopular = $isPopular;
        return $this;
    }

    public function getPower(): ?string
    {
        return $this->power;
    }

    public function setPower(?string $power): Car
    {
        $this->power = $power;
        return $this;
    }

    public function getStamp(): ?Stamp
    {
        return $this->stamp;
    }

    public function setStamp(?Stamp $stamp): static
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVolume(): ?string
    {
        return $this->volume;
    }

    /**
     * @param string|null $volume
     * @return Car
     */
    public function setVolume(?string $volume): Car
    {
        $this->volume = $volume;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMileageOneCharge(): ?string
    {
        return $this->mileageOneCharge;
    }

    /**
     * @param string|null $mileageOneCharge
     * @return Car
     */
    public function setMileageOneCharge(?string $mileageOneCharge): Car
    {
        $this->mileageOneCharge = $mileageOneCharge;
        return $this;
    }

}
