<?php

namespace Tests\Feature\Parsing\CarModel\DromRu;

use App\Services\Parsing\CarModel\DromRu\CarModelPageParser;
use App\Services\Parsing\CarModel\DromRu\ValueObjects\CarModelPage;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarModelPageParserTest extends TestCase
{
    #[Test]
    public function it_can_parse_items(): void
    {
        $parser = new CarModelPageParser();

        $parsedItems = $parser->parse(
            CarModelPage::fake('
                <a data-ga-stats-name="model_from_list"] href="test">model</a>
                <a data-ga-stats-name="model_from_list"] href="test-2">model 2</a>
            ')
        );

        $this->assertCount(2, $parsedItems);
    }

    #[Test]
    #[DataProvider('dromRuUrlDataProvider')]
    public function it_validates_car_model_page_url(string $url): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CarModelPage($url);
    }

    public static function dromRuUrlDataProvider(): array
    {
        return [
            [''],
            ['not-drom.ru-url'],
            ['https://www.drom.ru/not-catalog-page/'],
        ];
    }
}
