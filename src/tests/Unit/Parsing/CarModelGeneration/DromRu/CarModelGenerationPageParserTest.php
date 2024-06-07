<?php

namespace Tests\Feature\Parsing\CarModelGeneration\DromRu;

use App\Services\Parsing\CarModelGeneration\DromRu\CarModelGenerationPageParser;
use App\Services\Parsing\CarModelGeneration\DromRu\ValueObjects\CarModelGenerationPage;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CarModelGenerationPageParserTest extends TestCase
{
    #[Test]
    public function it_can_parse_car_model_generation(): void
    {
        $parser = new CarModelGenerationPageParser();

        $expectedRegion = 'region';
        $expectedImage = 'http://image-url';
        $expectedUrl = 'some-url';
        $expectedName = 'generation name';
        $expectedTime = 'start - end';
        $expectedHumanReadableName = '4 generation';

        $parsedItems = $parser->parse(CarModelGenerationPage::fake("
            <div id='region'>{$expectedRegion}</div>
            <div data-ga-stats-name='generations_outlet_item'>
                <a href='{$expectedUrl}' data-ftid='component_article'>
                    <div>
                        <div>
                            <img
                                src='{$expectedImage}' />
                        </div>
                    </div>
                    <div>
                        <span size='1' data-ftid='component_article_caption'>
                            <span>{$expectedName}
                                {$expectedTime}<!-- --> 
                            </span>
                        </span>
                    </div>
                    <div data-ftid='component_article_extended-info'>
                        <div>{$expectedHumanReadableName}</div>
                        <div>4G5</div>
                        <div>some text</div>
                    </div>
                </a>
            </div>
        "));

        $this->assertCount(1, $parsedItems);

        $parsedItem = $parsedItems[0];
        $this->assertSame($expectedRegion, $parsedItem->region);
        $this->assertSame($expectedName, $parsedItem->name);
        $this->assertSame($expectedHumanReadableName, $parsedItem->human_readable_name);
        $this->assertSame($expectedTime, $parsedItem->time);
        $this->assertSame($expectedImage, $parsedItem->image);
        $this->assertStringContainsString($expectedUrl, $parsedItem->url);
    }

    #[Test]
    #[DataProvider('dromRuUrlDataProvider')]
    public function it_validates_car_model_generation_page_url(string $url): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CarModelGenerationPage($url);
    }

    public static function dromRuUrlDataProvider(): array
    {
        return [
            [''],
            ['not-drom.ru-url'],
            ['https://www.drom.ru/not-generation-page/'],
        ];
    }
}
