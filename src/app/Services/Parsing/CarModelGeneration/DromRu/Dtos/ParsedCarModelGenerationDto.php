<?php

namespace App\Services\Parsing\CarModelGeneration\DromRu\Dtos;

readonly class ParsedCarModelGenerationDto
{
    public function __construct(
        public string $name,
        public string $human_readable_name,
        public string $time,
        public string $image,
        public string $region,
        public string $url,
    ) {
    }
}
