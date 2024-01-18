<?php

namespace App\Helper\DTO;

use App\Helper\Filter\Pagination;
use Doctrine\ORM\Tools\Pagination\Paginator;

readonly class PaginationDTO
{
    public function __construct(
        public mixed $data = [],
        public ?int  $count = null,
        public ?int  $pageCount = null,
        public ?int  $currentPage = null,
    )
    {
    }

    public static function createFromPaginator(?Paginator       $paginator,
                                               Pagination $paginationFilter,
                                               mixed            $data = null,
                                               ?int             $count = null,
    ): self
    {
        if (is_null($paginator) && (!$count & is_null($data))) throw new \InvalidArgumentException;
        $count = $count ?? $paginator->count();
        $pageCount = $paginationFilter->getLimit() ? ceil($count / $paginationFilter->getLimit()) : 1;
        return new self(
            $data ?? $paginator->getQuery()->getResult(),
            $count,
            $pageCount,
            (int)$paginationFilter->getPage()
        );
    }

    public static function createFromArray(array $data, Pagination $paginationFilter): self
    {
        $count = count($data);
        $limit = $paginationFilter->getLimit();
        return new self(
            $limit ? array_slice($data, $paginationFilter->getFirstMaxResult(), $limit) : $data,
            $limit ? $count : 1,
            $limit ? ceil($count / $limit) : 1,
            (int)$paginationFilter->getPage()
        );
    }
}