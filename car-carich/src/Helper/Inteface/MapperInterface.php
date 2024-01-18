<?php

namespace App\Helper\Inteface;

interface MapperInterface
{
    public function entitiesToResponseDTO(array $entities, string $dtoClass): array;

    public function entityToResponseDTO(object $entity, string $dtoClass): DTOInterface;
}