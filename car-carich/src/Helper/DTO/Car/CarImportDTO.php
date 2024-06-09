<?php

namespace App\Helper\DTO\Car;

use App\Helper\Enum\Type\CarType;

class CarImportDTO
{
    private ?string $name = null;
    private ?int $typeEngine = null;
    private ?string $country = null;
    private $weight = null;
    private ?string $size = null;
    private ?int $year = null;
    private ?string $battery = null;
    private ?int $mileage = null;
    private ?int $fullPrice = null;
    private ?int $standardPrice = null;
    private $isPopular = null;
    private ?string $power = null;
    private ?string $stamp = null;
    private ?string $volume = null;
    private ?string $mileageOneCharge = null;
    private array $images = [];
    private ?string $stampLogo = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return CarImportDTO
     */
    public function setName(?string $name): CarImportDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTypeEngine(): ?int
    {
        return $this->typeEngine;
    }

    public function setTypeEngine(string|null|int $typeEngine): CarImportDTO
    {
        if(is_string($typeEngine)) {
            $keyName = array_search(mb_strtolower($typeEngine), CarType::getNamesLower());
            if($keyName !== false) {
                $typeEngine = $keyName;
            } else {
                $typeEngine = CarType::PETROL->value;
            }
        }

        $this->typeEngine = $typeEngine;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return CarImportDTO
     */
    public function setCountry(?string $country): CarImportDTO
    {
        $this->country = $country;
        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight): CarImportDTO
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * @param string|null $size
     * @return CarImportDTO
     */
    public function setSize(?string $size): CarImportDTO
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     * @return CarImportDTO
     */
    public function setYear(?int $year): CarImportDTO
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBattery(): ?string
    {
        return $this->battery;
    }

    /**
     * @param string|null $battery
     * @return CarImportDTO
     */
    public function setBattery(?string $battery): CarImportDTO
    {
        $this->battery = $battery;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    /**
     * @param int|null $mileage
     * @return CarImportDTO
     */
    public function setMileage(?int $mileage): CarImportDTO
    {
        $this->mileage = $mileage;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFullPrice(): ?int
    {
        return $this->fullPrice;
    }

    /**
     * @param int|null $fullPrice
     * @return CarImportDTO
     */
    public function setFullPrice(?int $fullPrice): CarImportDTO
    {
        $this->fullPrice = $fullPrice;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStandardPrice(): ?int
    {
        return $this->standardPrice;
    }

    /**
     * @param int|null $standardPrice
     * @return CarImportDTO
     */
    public function setStandardPrice(?int $standardPrice): CarImportDTO
    {
        $this->standardPrice = $standardPrice;
        return $this;
    }

    public function getIsPopular()
    {
        return $this->isPopular;
    }

    public function setIsPopular($isPopular): CarImportDTO
    {
        if(is_string($isPopular)) {
            $isPopular = $isPopular == 'Да';
        }

        $this->isPopular = $isPopular;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPower(): ?string
    {
        return $this->power;
    }

    /**
     * @param string|null $power
     * @return CarImportDTO
     */
    public function setPower(?string $power): CarImportDTO
    {
        $this->power = $power;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStamp(): ?string
    {
        return $this->stamp;
    }

    /**
     * @param string|null $stamp
     * @return CarImportDTO
     */
    public function setStamp(?string $stamp): CarImportDTO
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
     * @return CarImportDTO
     */
    public function setVolume(?string $volume): CarImportDTO
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
     * @return CarImportDTO
     */
    public function setMileageOneCharge(?string $mileageOneCharge): CarImportDTO
    {
        $this->mileageOneCharge = $mileageOneCharge;
        return $this;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array $images
     * @return CarImportDTO
     */
    public function setImages(array $images): CarImportDTO
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStampLogo(): ?string
    {
        return $this->stampLogo;
    }

    /**
     * @param string|null $stampLogo
     * @return CarImportDTO
     */
    public function setStampLogo(?string $stampLogo): CarImportDTO
    {
        $this->stampLogo = $stampLogo;
        return $this;
    }

    public static function getKeysForImport(): array
    {
        return [
            'name',
            'typeEngine',
            'country',
            'weight',
            'size',
            'year',
            'battery',
            'mileage',
            'fullPrice',
            'standardPrice',
            'isPopular',
            'power',
            'stamp',
            'volume',
            'mileageOneCharge',
            'stampLogo',
            'images',
        ];
    }
}