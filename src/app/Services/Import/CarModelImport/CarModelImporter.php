<?php

namespace App\Services\Import\CarModelImport;

use App\Models\CarModel;

class CarModelImporter
{
    public function import(CarModelImportDto $importDto): void
    {
        CarModel::query()
            ->firstOrNew([
                'external_id' => $importDto->externalId,
            ], [
                'name' => $importDto->name,
            ])
            ->save();
    }
}
