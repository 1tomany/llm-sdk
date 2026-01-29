<?php

namespace OneToMany\AI\Client\Mock\Trait;

use function bin2hex;
use function random_bytes;
use function sprintf;
use function strtolower;

trait GenerateUriTrait
{
    /**
     * @param non-empty-string $prefix
     * @param positive-int $suffixBytes
     *
     * @return non-empty-string
     */
    private function generateUri(string $prefix, int $suffixBytes = 4): string
    {
        return sprintf('mock_%s_%s', strtolower($prefix), bin2hex(random_bytes($suffixBytes)));
    }
}
