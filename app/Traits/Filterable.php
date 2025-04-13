<?php

namespace App\Traits;

trait Filterable
{
    protected $filter = [];

    public function filter(): array
    {
        return $this->filter;
    }

    public function setFilter($filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    public function pushFilter(...$filter): self
    {
        array_push($this->filter, $filter);
        return $this;
    }

    public function resetFilter(): self
    {
        $this->filter = [];
        return $this;
    }
}
