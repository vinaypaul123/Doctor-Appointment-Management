<?php

namespace App\DataTransferObjects;

use App\Contracts\DtoContract;

readonly abstract class Dto implements DtoContract
{
    public array $__except;

    abstract static function build(array $data): self;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
