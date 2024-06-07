<?php

namespace App\Services\Parsing\CarModelGeneration\DromRu;

use App\Services\Parsing\CarModelGeneration\DromRu\Dtos\ParsedCarModelGenerationDto;
use App\Services\Parsing\CarModelGeneration\DromRu\ValueObjects\CarModelGenerationPage;
use DOMElement;
use Symfony\Component\DomCrawler\Crawler;

class CarModelGenerationPageParser
{
    /** @return array<ParsedCarModelGenerationDto> */
    public function parse(CarModelGenerationPage $pageBody): array
    {
        $parsedModelGenerations = [];

        /** @var DOMElement $item*/
        foreach ($this->getItems($pageBody) as $item) {
            [$name, $time] = $this->guessNameAndTime($item);

            $parsedModelGenerations[] = new ParsedCarModelGenerationDto(
                $name,
                $this->guessHumanReadableName($item),
                $time,
                $this->getImage($item),
                $this->guessRegion($item),
                $this->getLink($item, $pageBody)
            );
        }

        return $parsedModelGenerations;
    }

    private function guessNameAndTime(DOMElement $item): array
    {
        $crawler = new Crawler($item);
        $nodes = $crawler->filter('span');

        return collect(explode("\n", $nodes->eq(1)->html()))
            ->map(fn (string $text) => trim($text))
            ->map(fn (string $text) => preg_replace('/\<!-- -->/', '', $text))
            ->filter()
            ->toArray();
    }

    private function guessRegion(DOMElement $item): string
    {
        $crawler = new Crawler($item->parentNode->parentNode);

        return $crawler->filter('[id]')->text();
    }

    private function guessHumanReadableName(DOMElement $item): string
    {
        $crawler = new Crawler($item);

        return $crawler
            ->filter('div[data-ftid="component_article_extended-info"] > div')
            ->eq(0)
            ->text();
    }

    private function getImage(DOMElement $item): string
    {
        $crawler = new Crawler($item);

        return $crawler
            ->filter('img')
            ->image()
            ->getUri();
    }

    private function getLink(DOMElement $item, CarModelGenerationPage $pageBody): string
    {
        $crawler = new Crawler($item, uri: $pageBody->url);

        return $crawler
            ->filter('a')
            ->link()
            ->getUri();
    }

    private function getItems(CarModelGenerationPage $pageBody): Crawler
    {
        $crawler = new Crawler($pageBody->body);

        $crawler->filter('style')->each(function (Crawler $node) {
            $node->getNode(0)->parentNode->removeChild($node->getNode(0));
        });

        $crawler->filter('svg')->each(function (Crawler $node) {
            $node->getNode(0)->parentNode->removeChild($node->getNode(0));
        });

        return $crawler->filter('[data-ga-stats-name="generations_outlet_item"]');
    }
}
