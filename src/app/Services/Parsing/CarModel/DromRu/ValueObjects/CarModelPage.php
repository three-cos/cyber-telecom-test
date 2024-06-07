<?php

namespace App\Services\Parsing\CarModel\DromRu\ValueObjects;

use InvalidArgumentException;

readonly class CarModelPage
{
    public function __construct(
        public string $url,
        public ?string $body = null
    ) {
        throw_unless(
            preg_match('/https:\/\/www\.drom\.ru\/catalog\/[\w\-]+\//', $url),
            new InvalidArgumentException("{$url} is not a valid drom.ru models page")
        );
    }

    public static function fake(?string $body = null): self
    {
        return new self(
            'https://www.drom.ru/catalog/test-category/',
            $body
        );
    }
}
