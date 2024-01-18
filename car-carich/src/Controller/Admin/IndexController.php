<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('', name: 'admin_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('app_car_index');
    }
}