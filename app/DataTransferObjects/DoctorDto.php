<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Fluent;

readonly class DoctorDto extends Dto
{
    public function __construct(
        public string $name,
        public string $specialization,
        public string $qualification,
        public string $description,
    ) {}

    public static function build(array $array): self
    {
        $data = new Fluent($array);

        return new self(
            $data->name,
            $data->specialization,
            $data->qualification,
            $data->description,
        );
    }
}
