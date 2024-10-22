<?php

namespace App\Controller\Api;

use App\Helper\DTO\{Car\CarLkResponse, PaginationDTO};
use App\Helper\Filter\{CatalogueFilter, Pagination};
use App\Helper\Mapper\{Mapper};
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LkController extends AbstractController
{
    public function __construct(
        protected CarRepository       $carRepository,
        protected Mapper              $mapper,
        protected SerializerInterface $serializer,
    )
    {
    }

    #[Route('/popular/cars', name: 'popular_cars', methods: ['GET'])]
    public function getPopularCars(Request $request): JsonResponse
    {
        $pagination = $this->serializer->deserialize(json_encode($request->query->all()), Pagination::class, 'json');
        $paginator = $this->carRepository->getPaginatorPopularCars($pagination);

        return $this->json(
            PaginationDTO::createFromPaginator(
                null,
                $pagination,
                $this->mapper->entitiesToResponseDTO(
                    $paginator->getQuery()->getResult(),
                    CarLkResponse::class
                ),
                $paginator->count()
            )
        );
    }

    #[Route('/catalogue', name: 'catalogue', methods: ['GET'])]
    public function getCatalogueCars(Request $request): JsonResponse
    {
        $query = json_encode($request->query->all());
        $pagination = $this->serializer->deserialize($query, Pagination::class, 'json');
        $catalogueFilter = $this->serializer->deserialize($query, CatalogueFilter::class, 'json');
        $paginator = $this->carRepository->getPaginatorCatalogue($catalogueFilter, $pagination);

        return $this->json(
            PaginationDTO::createFromPaginator(
                null,
                $pagination,
                $this->mapper->entitiesToResponseDTO(
                    $paginator->getQuery()->getResult(),
                    CarLkResponse::class
                ),
                $paginator->count()
            )
        );
    }
}