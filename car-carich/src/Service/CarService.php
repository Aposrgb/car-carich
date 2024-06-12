<?php

namespace App\Service;

use App\Entity\{Car, Country, Stamp};
use App\Helper\{DTO\Car\CarCountryStampDataDTO, DTO\Car\CarImportDTO, Enum\Type\CarType, Exception\ApiException};
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\{Client, Exception\GuzzleException, Promise\Utils};
use Psr\Http\Message\ResponseInterface;
use PhpOffice\PhpSpreadsheet\{Reader\Xlsx, RichText\ITextElement, RichText\RichText, Worksheet\Row};
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
        #[Autowire(env: 'YANDEX_DISK_API')]
        private string                 $yandexDiskApi,
    )
    {
    }

    public function importCars($pathXls): void
    {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($this->projectDir . $pathXls);
        $worksheet = $spreadsheet->getActiveSheet();

        $importKeys = CarImportDTO::getKeysForImport();
        $carsCountryStampDataDTO = $this->getCarCountryStampDataDTO();

        $client = new Client();
        foreach ($worksheet->getRowIterator(2) as $row) {
            $carDTO = $this->getCarImportDTOByRowValues($row, $importKeys);

            if (array_key_exists(mb_strtolower($carDTO->getName()), $carsCountryStampDataDTO->getCars())) {
                continue;
            }

            $car = $this->mapCarFromDTO($carDTO);
            $car->setTypeEngine(CarType::PETROL->value);

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

//            if (!empty($carDTO->getStampLogo()) and !is_null($car->getStamp()) and is_null($car->getStamp()->getIcon())) {
//                try {
//                    $path = $this->saveOutputAndGetPath($client, $carDTO->getStampLogo(), FileUploadService::STAMP_ICON_PATH);
//                    if (is_null($path)) {
//                        $this->fileUploadService->deleteFile($pathXls);
//                        throw new ApiException(
//                            'Изображение логотипа марки полученное по ссылке не поддерживается, поддерживаются: ' . implode(', ', array_values(FileUploadService::MIME_TYPES))
//                        );
//                    }
//                    $car->getStamp()->setIcon($path);
//                } catch (GuzzleException) {
//                    $this->fileUploadService->deleteFile($pathXls);
//                    throw new ApiException('Неверная ссылка на логотип марки машины');
//                }
//            }

            try {
                if (!empty($carDTO->getImages())) {
                    $path = $this->saveOutputAndGetPath($client, $carDTO->getImages(), FileUploadService::CAR_IMAGES_PATH);
                    if (!empty($path)) {
                        $car->setImages($path);
                    }
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

    private function getCarImportDTOByRowValues(Row $row, array $keys): CarImportDTO
    {
        $index = 0;
        $data = [];
        foreach ($row->getCellIterator() as $cell) {
            if (is_null($cell->getValue())) {
                $index++;
                continue;
            }
            if (!array_key_exists($index, $keys)) {
                $index++;
                continue;
            }
            $key = $keys[$index];
            if (!$key) {
                $index++;
                continue;
            }
            if ($key == 'name') {
                $data[$key] = trim($data['stamp']) . ' ' . trim($cell->getValue());
            } else {
                $value = $cell->getValue();
                if ($value instanceof RichText) {
                    $textElements = $value->getRichTextElements();
                    /** @var ITextElement $textEl */
                    $textEl = end($textElements);
                    if (!$textEl) {
                        $index++;
                        continue;
                    }
                    $value = trim($textEl->getText());
                }
                $data[$key] = $value;
            }
            $index++;
        }

        return $this->serializer->deserialize(json_encode($data), CarImportDTO::class, 'json');
    }

    /**
     * @throws GuzzleException
     */
    private function saveOutputAndGetPath(Client $client, string $url, string $targetDir): ?array
    {
        $response = $client->get($this->yandexDiskApi . '?public_key=' . $url);
        $dataUrl = json_decode($response->getBody()->getContents(), true);
        $keyEmbedded = '_embedded';
        $keyItems = 'items';
        if (!array_key_exists($keyEmbedded, $dataUrl)) {
            return null;
        }

        if (!array_key_exists($keyItems, $dataUrl[$keyEmbedded])) {
            return null;
        }
        $data = [];
        $keyFile = 'file';
        $promises = [];
        foreach ($dataUrl[$keyEmbedded][$keyItems] as $item) {
            if (!array_key_exists($keyFile, $item)) {
                continue;
            }
            $promises[] = $client
                ->getAsync($item[$keyFile])
                ->then(
                    onFulfilled: function (ResponseInterface $response) use (&$data, $targetDir) {
                        $mime = $response->getHeader('Content-Type')[0];
                        $fileName = $this->fileUploadService->getUniqueFileName();
                        if (!array_key_exists($mime, FileUploadService::MIME_TYPES)) {
                            return null;
                        }
                        $fileName .= '.' . FileUploadService::MIME_TYPES[$mime];
                        $data[] = $this->fileUploadService->saveOutput($fileName, $response->getBody()->getContents(), $targetDir);
                    }
                );

        }
        $promise = Utils::settle($promises);
        $promise->wait();

        return $data;
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