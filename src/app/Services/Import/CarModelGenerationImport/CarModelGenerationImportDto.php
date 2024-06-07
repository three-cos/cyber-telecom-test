<?php

namespace App\Services\Import\CarModelGenerationImport;

class CarModelGenerationImportDto
{
    public function __construct(
        public int $model_id,
        public string $market,
        public string $name,
        public string $human_readable_name,
        public string $external_id,
        public string $time,
        public string $url,
        public ?string $image
    ) {
    }
}
