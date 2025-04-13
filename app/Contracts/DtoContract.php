<?php

namespace App\Contracts;

interface DtoContract
{
    public static function build(array $array): self;
    public function toArray(): array;
}
