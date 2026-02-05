<?php

namespace OneToMany\AI\Client\Mock\Trait;

use function bin2hex;
use function random_bytes;
use function sprintf;
use function strtolower;

trait GenerateUriTrait
{
    /**
     * @param non-empty-lowercase-string $prefix
     * @param positive-int $byteCount
     *
     * @return non-empty-lowercase-string
     */
    protected function generateUri(string $prefix, int $byteCount = 4): string
    {
        return strtolower(sprintf('%s_%s', $prefix, bin2hex(random_bytes($byteCount))));
    }
}
