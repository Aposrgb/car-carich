<?php

namespace App\Helper\Filter;

class Pagination
{
    protected null|string|int $page = 1;

    protected null|string|int $limit = 10;

    public function getFirstMaxResult(): int
    {
        return $this->getLimit() * ($this->getPage() - 1);
    }

    public function getPage(): string|int
    {
        return $this->page ?? 1;
    }

    public function setPage(null|string|int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getLimit(): string|int
    {
        return $this->limit ?? 10;
    }

    public function setLimit(null|string|int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
}