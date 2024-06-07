<?php

namespace App\Services\Parsing\CarModelGeneration\DromRu\ValueObjects;

use InvalidArgumentException;

readonly class CarModelGenerationPage
{
    public function __construct(
        public string $url,
        public ?string $body = null
    ) {
        throw_unless(
            preg_match('/https:\/\/www\.drom\.ru\/catalog\/[\w\-]+\/[\w\-]+\//', $url),
            new InvalidArgumentException("{$url} is not a valid drom.ru model generation page")
        );
    }

    public static function fake(?string $body = null): self
    {
        return new self(
            'https://www.drom.ru/catalog/test-model/test-generation/',
            $body
        );
    }
}
