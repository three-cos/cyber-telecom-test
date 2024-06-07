<?php

namespace App\Console\Commands;

use App\Models\CarModel;
use App\Services\Import\CarModelGenerationImport\CarModelGenerationImportDto;
use App\Services\Import\CarModelGenerationImport\CarModelGenerationImporter;
use App\Services\Parsing\CarModelGeneration\DromRu\CarModelGenerationPageParser;
use App\Services\Parsing\CarModelGeneration\DromRu\ValueObjects\CarModelGenerationPage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class ParseModelGenerations extends Command
{
    protected $signature = 'app:parse-model-generations';

    protected $description = 'Parse drom.ru generations for application car models';

    public function handle(CarModelGenerationPageParser $parser, CarModelGenerationImporter $importer)
    {
        try {
            $carModels = CarModel::query()
                ->get();

            foreach ($carModels as $carModel) {
                $parsedItems = $parser->parse(
                    new CarModelGenerationPage(
                        $remoteGenerationPage = $carModel->external_id,
                        Http::get($remoteGenerationPage)
                            ->body()
                    )
                );

                $this->info(
                    sprintf(
                        'There are %s generations for model %s on %s page',
                        count($parsedItems),
                        $carModel->name,
                        $remoteGenerationPage
                    ),
                    'v'
                );

                $this->import($parsedItems, $importer, $carModel);
            }
        } catch (Throwable $th) {
            report($th);

            $this->error($th->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function import(array $parsedItems, CarModelGenerationImporter $importer, CarModel $carModel): void
    {
        foreach ($parsedItems as $parsedItem) {
            try {
                $importer->import(
                    new CarModelGenerationImportDto(
                        $carModel->id,
                        $parsedItem->region,
                        $parsedItem->name,
                        $parsedItem->human_readable_name,
                        $parsedItem->url,
                        $parsedItem->time,
                        $parsedItem->url,
                        $parsedItem->image
                    )
                );

                $this->info(
                    sprintf(
                        'Imported %s',
                        $parsedItem->name,
                    ),
                    'v'
                );
            } catch (Throwable $th) {
                report($th);

                $this->error(
                    sprintf(
                        'Error while importing %s',
                        $parsedItem->name,
                    ),
                    'v'
                );

                continue;
            }
        }
    }
}
