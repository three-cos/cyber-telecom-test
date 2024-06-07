<?php

namespace App\Services\Import\CarModelImport;

use App\Services\Parsing\CarModel\DromRu\Dtos\ParsedCarModelDto;

class CarModelImportDto
{
    public function __construct(
        public string $name,
        public string $externalId,
    ) {
    }

    public static function fromParsedModelDto(ParsedCarModelDto $parsedModel): self
    {
        return new self(
            $parsedModel->name,
            $parsedModel->url
        );
    }
}
