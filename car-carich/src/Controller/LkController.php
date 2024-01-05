<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LkController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('lk/index.html.twig');
    }

    #[Route('/catalog', name: 'catalog', methods: ['GET'])]
    public function catalog(): Response
    {
        return $this->render('lk/catalog.html.twig');
    }

    #[Route('/car-detail/{car<\d+>}', name: 'car_detail', methods: ['GET'])]
    public function carDetail(int $car): Response
    {
        return $this->render('lk/car_detail.html.twig');
    }

     #[Route('/about-us', name: 'about_us', methods: ['GET'])]
    public function aboutUs(): Response
    {
        return $this->render('lk/about_us.html.twig');
    }
}