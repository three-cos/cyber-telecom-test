<?php

namespace App\Services\Parsing\CarModel\DromRu\Dtos;

readonly class ParsedCarModelDto
{
    public function __construct(
        public string $name,
        public string $url,
    ) {
    }
}
