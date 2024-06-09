<?php

namespace App\Service;

use App\Entity\{Car, Country, Stamp};
use App\Helper\{DTO\Car\CarCountryStampDataDTO, DTO\Car\CarImportDTO, Exception\ApiException};
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\{Client, Exception\GuzzleException};
use PhpOffice\PhpSpreadsheet\{Reader\Xlsx, Worksheet\Row};
use Symfony\Component\{DependencyInjection\Attribute\Autowire,
    HttpFoundation\File\UploadedFile,
    Serializer\SerializerInterface
};

class CarService
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public')]
        private string                 $projectDir,
        private FileUploadService      $fileUploadService,
        private SerializerInterface    $serializer,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function importCars(UploadedFile $file): void
    {
        $pathXls = $this->fileUploadService->upload($file, FileUploadService::TEMP_PATH);

        $reader = new Xlsx();
        $spreadsheet = $reader->load($this->projectDir . $pathXls);
        $worksheet = $spreadsheet->getActiveSheet();

        $importKeys = CarImportDTO::getKeysForImport();
        $countImportKeys = count($importKeys);

        $carsCountryStampDataDTO = $this->getCarCountryStampDataDTO();

        $client = new Client();
        foreach ($worksheet->getRowIterator(2) as $row) {
            $carDTO = $this->getCarImportDTOByRowValues($row, $importKeys, $countImportKeys);

            if (array_key_exists(mb_strtolower($carDTO->getName()), $carsCountryStampDataDTO->getCars())) {
                continue;
            }

            $car = $this->mapCarFromDTO($carDTO);

            if (!empty($carDTO->getCountry())) {
                $lowerNameCountry = mb_strtolower($carDTO->getCountry());
                $countries = $carsCountryStampDataDTO->getCountries();
                if (array_key_exists($lowerNameCountry, $countries)) {
                    $car->setCountry($countries[$lowerNameCountry]);
                } else {
                    $country = new Country($carDTO->getCountry());
                    $car->setCountry($country);
                    $countries[$lowerNameCountry] = $country;
                    $carsCountryStampDataDTO->setCountries($countries);
                }
            }

            if (!empty($carDTO->getStamp())) {
                $lowerNameStamp = mb_strtolower($carDTO->getStamp());
                $stamps = $carsCountryStampDataDTO->getStamps();
                if (array_key_exists($lowerNameStamp, $stamps)) {
                    $car->setStamp($stamps[$lowerNameStamp]);
                } else {
                    $stamp = new Stamp($carDTO->getStamp());
                    $car->setStamp($stamp);
                    $stamps[$lowerNameStamp] = $stamp;
                    $carsCountryStampDataDTO->setStamps($stamps);
                }
            }

            if (!empty($carDTO->getStampLogo()) and !is_null($car->getStamp()) and is_null($car->getStamp()->getIcon())) {
                try {
                    $path = $this->saveOutputAndGetPath($client, $carDTO->getStampLogo(), FileUploadService::STAMP_ICON_PATH);
                    if (is_null($path)) {
                        $this->fileUploadService->deleteFile($pathXls);
                        throw new ApiException(
                            'Изображение логотипа марки полученное по ссылке не поддерживается, поддерживаются: ' . implode(', ', array_values(FileUploadService::MIME_TYPES))
                        );
                    }
                    $car->getStamp()->setIcon($path);
                } catch (GuzzleException) {
                    $this->fileUploadService->deleteFile($pathXls);
                    throw new ApiException('Неверная ссылка на логотип марки машины');
                }
            }

            try {
                foreach ($carDTO->getImages() as $image) {
                    $path = $this->saveOutputAndGetPath($client, $image, FileUploadService::CAR_IMAGES_PATH);
                    if (is_null($path)) {
                        $this->fileUploadService->deleteFile($pathXls);
                        throw new ApiException(
                            'Изображение машины полученное по ссылке не поддерживается, поддерживаются: ' . implode(', ', array_values(FileUploadService::MIME_TYPES))
                        );
                    }
                    $car->addImage($path);
                }
            } catch (GuzzleException) {
                $this->fileUploadService->deleteFile($pathXls);
                throw new ApiException('Неверная ссылка на фотографию машины');
            }

            $this->entityManager->persist($car);
        }
        $this->fileUploadService->deleteFile($pathXls);
        $this->entityManager->flush();
    }

    private function mapCarFromDTO(CarImportDTO $carDTO): Car
    {
        return (new Car())
            ->setName($carDTO->getName())
            ->setTypeEngine($carDTO->getTypeEngine())
            ->setWeight($carDTO->getWeight())
            ->setSize($carDTO->getSize())
            ->setYear($carDTO->getYear())
            ->setBattery($carDTO->getBattery())
            ->setMileage($carDTO->getMileage())
            ->setFullPrice($carDTO->getFullPrice())
            ->setStandardPrice($carDTO->getStandardPrice())
            ->setIsPopular($carDTO->getIsPopular())
            ->setPower($carDTO->getPower())
            ->setVolume($carDTO->getVolume())
            ->setMileageOneCharge($carDTO->getMileageOneCharge());
    }

    private function getCarImportDTOByRowValues(Row $row, array $keys, int $countKeys): CarImportDTO
    {
        $index = 0;
        $data = [];
        foreach ($row->getCellIterator() as $cell) {
            if (is_null($cell->getValue())) {
                $index++;
                continue;
            }
            if ($index >= $countKeys - 1) {
                $key = $keys[$countKeys - 1];
                $data[$key][] = $cell->getValue();
            } else {
                $key = $keys[$index];
                $data[$key] = $cell->getValue();
            }
            $index++;
        }
        return $this->serializer->deserialize(json_encode($data), CarImportDTO::class, 'json');
    }

    /**
     * @throws GuzzleException
     */
    private function saveOutputAndGetPath(Client $client, string $url, string $targetDir): ?string
    {
        $response = $client->get($url);
        $mime = $response->getHeader('Content-Type')[0];
        $fileName = $this->fileUploadService->getUniqueFileName();
        if (!array_key_exists($mime, FileUploadService::MIME_TYPES)) {
            return null;
        }
        $fileName .= '.' . FileUploadService::MIME_TYPES[$mime];
        return $this->fileUploadService->saveOutput($fileName, $response->getBody()->getContents(), $targetDir);
    }

    private function getCarCountryStampDataDTO(): CarCountryStampDataDTO
    {
        return new CarCountryStampDataDTO(
            cars: $this->entityManager->getRepository(Car::class)->findBySelectPropertyName(),
            countries: $this->entityManager->getRepository(Country::class)->findBySelectPropertyName(),
            stamps: $this->entityManager->getRepository(Stamp::class)->findBySelectPropertyName(),
        );
    }
}