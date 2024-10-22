<?php

namespace App\Controller;

use App\Entity\Car;
use App\Helper\Enum\Type\SettingsType as EnumSettingsType;
use App\Helper\DTO\Car\CarLkResponse;
use App\Helper\Mapper\Mapper;
use App\Repository\SettingsRepository;
use App\Service\LkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LkController extends AbstractController
{
    public function __construct(
        private readonly LkService $lkService,
        private readonly Mapper    $mapper,
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('lk/index.html.twig', [
            'LkFilter' => $this->lkService->createLkFilterIndex()
        ]);
    }

    #[Route('/catalog', name: 'catalog', methods: ['GET'])]
    public function catalog(): Response
    {
        return $this->render('lk/catalog.html.twig', [
            'LkFilter' => $this->lkService->createLkFilterCatalogue()
        ]);
    }

    #[Route('/car-detail/{car<\d+>}', name: 'car_detail', methods: ['GET'])]
    public function carDetail(Car $car): Response
    {
        return $this->render('lk/car_detail.html.twig', [
            'car' => $this->mapper->entityToResponseDTO($car, CarLkResponse::class)
        ]);
    }

    #[Route('/about-us', name: 'about_us', methods: ['GET'])]
    public function aboutUs(SettingsRepository $settingsRepository): Response
    {
        $settings = $settingsRepository->findBy([], ['type' => 'ASC']);
        $socialNetworks = [];
        foreach ($settings as $setting) {
            if ($setting->getType() == EnumSettingsType::CONTRACT->value) {
                $contract = $setting->getImg();
                continue;
            }
            $socialNetworks[] = $setting;
        }
        return $this->render('lk/about_us.html.twig', [
            'contract' => $contract ?? null,
            'socialNetworks' => $socialNetworks
        ]);
    }

    #[Route('/profile', name: "app_lk_profile", methods: ['GET'])]
    public function getProfile(): Response
    {

        return $this->render('lk/profile/profile.html.twig');
    }
}