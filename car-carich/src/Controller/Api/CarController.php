<?php

namespace App\Controller\Api;

use App\Helper\DTO\Car\CarCreateRequestDTO;
use App\Helper\Exception\ApiException;
use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/car')]
class CarController extends AbstractController
{
    #[Route('/create', name: 'app_car_create', methods: ['POST'])]
    public function create(
        #[Autowire(env: 'SERVICE_CREDENTIALS_LOGIN')]
        string              $login,
        #[Autowire(env: 'SERVICE_CREDENTIALS_PASS')]
        string              $password,
        Request             $request,
        CarService          $carService,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        $headers = $request->headers->all();
        if (array_key_exists('login', $headers)
            and array_key_exists('password', $headers)) {
            if (current($headers['login']) == $login and current($headers['password']) == $password) {
                $carService->create($serializer->deserialize($request->getContent(), CarCreateRequestDTO::class, 'json'));
                return $this->json(['status' => 'ok']);
            }
        }
        throw new ApiException('Not valid credentials', status: 403);
    }
}