<?php

namespace App\Helper\Mapper;

use App\Helper\Inteface\DTOInterface;
use App\Helper\Inteface\MapperInterface;

class Mapper implements MapperInterface
{
    public function entitiesToResponseDTO(array $entities, string $dtoClass): array
    {
        return array_map(fn($entity) => $this->entityToResponseDTO($entity, $dtoClass), $entities);
    }

    public function entityToResponseDTO(object $entity, string $dtoClass): DTOInterface
    {
        return new $dtoClass($entity);
    }
}