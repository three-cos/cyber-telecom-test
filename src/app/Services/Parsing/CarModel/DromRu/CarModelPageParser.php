<?php

namespace App\Services\Parsing\CarModel\DromRu;

use App\Services\Parsing\CarModel\DromRu\Dtos\ParsedCarModelDto;
use App\Services\Parsing\CarModel\DromRu\ValueObjects\CarModelPage;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;

class CarModelPageParser
{
    /** @return array<ParsedCarModelDto> */
    public function parse(CarModelPage $pageBody): array
    {
        $parsedModels = [];

        foreach ($this->getItems($pageBody) as $item) {
            $parsedModels[] = new ParsedCarModelDto(
                $item->textContent,
                $item->getAttribute('href')
            );
        }

        return $parsedModels;
    }

    /** @return DOMNodeList<DOMElement> */
    private function getItems(CarModelPage $pageBody): DOMNodeList
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($pageBody->body);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        return $xpath->query("//a[@data-ga-stats-name='model_from_list']");
    }
}
