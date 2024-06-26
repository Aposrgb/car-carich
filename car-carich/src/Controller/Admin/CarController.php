<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Form\CarType;
use App\Helper\{DTO\Car\CarAdminResponse, Exception\ApiException, Mapper\Mapper};
use App\Repository\CarRepository;
use App\Service\{CarService, FileUploadService};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\{HttpFoundation\File\UploadedFile,
    HttpFoundation\JsonResponse,
    HttpFoundation\Request,
    HttpFoundation\Response,
    Routing\Annotation\Route
};

#[Route('/car')]
class CarController extends AbstractController
{
    public function __construct(
        protected Mapper $mapper,
    )
    {
    }


    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('admin/car/index.html.twig', [
            'cars' => $this->mapper->entitiesToResponseDTO(
                $carRepository->findAll(), CarAdminResponse::class
            ),
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allFiles = $request->files->all()['car'] ?? [];
            if (array_key_exists('images', $allFiles) and !empty($allFiles['images'])) {
                $images = [];
                foreach ($allFiles['images'] as $image) {
                    if ($image) {
                        $path = $fileUploadService->upload($image, FileUploadService::CAR_IMAGES_PATH);
                        $images[] = $path;
                    }
                }
                $car->setImages($images);
            }
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('admin/car/show.html.twig', [
            'car' => $this->mapper->entityToResponseDTO($car, CarAdminResponse::class),
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allFiles = $request->files->all()['car'] ?? [];
            if (array_key_exists('images', $allFiles) and !empty($allFiles['images'])) {
                $images = $car->getImages();
                foreach ($allFiles['images'] as $image) {
                    if ($image) {
                        $path = $fileUploadService->upload($image, FileUploadService::CAR_IMAGES_PATH);
                        $images[] = $path;
                    }
                }
                $car->setImages($images);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{car<\d+>}', name: 'car_delete_image', methods: ['DELETE'])]
    public function deleteFile(Request $request, Car $car, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): JsonResponse
    {
        $query = $request->query->all();
        if (key_exists('index', $query) and
            is_numeric($query['index']) and
            array_key_exists($index = (int)$query['index'], $car->getImages())
        ) {
            $images = $car->getImages();
            $fileUploadService->deleteFile($images[$index]);
            unset($images[$index]);
            $car->setImages($images);
            $entityManager->flush();
        }
        return $this->json([], status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/import', name: 'upload_car_page', methods: ['GET'])]
    public function importExcelPage(): Response
    {
        return $this->render('admin/car/import.html.twig');
    }

    #[Route('/upload', name: 'upload_car', methods: ['POST'])]
    public function importExcel(Request $request, FileUploadService $fileUploadService): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if (!$file) {
            throw new ApiException('Файл не загружен');
        }
        if ($file->getClientOriginalExtension() != 'xlsx') {
            throw new ApiException('Неверный тип расширения файла');
        }

        $pathXls = $fileUploadService->upload($file, FileUploadService::TEMP_PATH);
        shell_exec("php bin/console car:import $pathXls > /dev/null & ");
        shell_exec("php ../bin/console car:import $pathXls > /dev/null & ");
        return $this->json([]);
    }
}
