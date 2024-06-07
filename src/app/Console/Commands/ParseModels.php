<?php

namespace App\Console\Commands;

use App\Services\Import\CarModelImport\CarModelImportDto;
use App\Services\Import\CarModelImport\CarModelImporter;
use App\Services\Parsing\CarModel\DromRu\CarModelPageParser;
use App\Services\Parsing\CarModel\DromRu\ValueObjects\CarModelPage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class ParseModels extends Command
{
    protected $signature = 'app:parse-models {remoteModelsPage}';

    protected $description = 'Command description';

    public function handle(CarModelPageParser $parser, CarModelImporter $importer): int
    {
        try {
            $page = new CarModelPage(
                $remoteModelsPage = $this->argument('remoteModelsPage'),
                Http::get($remoteModelsPage)
                    ->body()
            );

            $parsedDocuments = $parser->parse($page);

            $this->info(
                sprintf(
                    'There are %s models on %s page',
                    count($parsedDocuments),
                    $remoteModelsPage
                ),
                'v'
            );

            foreach ($parsedDocuments as $parsedDocument) {
                $importer->import(
                    CarModelImportDto::fromParsedModelDto(
                        $parsedDocument
                    )
                );

                $this->info(
                    sprintf(
                        'Imported %s',
                        $parsedDocument->name,
                    ),
                    'v'
                );
            }
        } catch (Throwable $th) {
            report($th);

            $this->error($th->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
