<?php

namespace App\Services\Import\CarModelGenerationImport;

use App\Models\CarModelGeneration;

class CarModelGenerationImporter
{
    public function import(CarModelGenerationImportDto $importDto): void
    {
        CarModelGeneration::query()
            ->firstOrNew([
                'external_id' => $importDto->external_id,
            ], [
                'model_id' => $importDto->model_id,
                'name' => $importDto->name,
                'human_readable_name' => $importDto->human_readable_name,
                'time' => $importDto->time,
                'url' => $importDto->url,
                'image' => $importDto->image,
                'market' => $importDto->market,
            ])
            ->save();
    }
}
