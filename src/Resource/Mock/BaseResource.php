<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use function bin2hex;
use function implode;
use function ltrim;
use function random_bytes;
use function sprintf;
use function strtolower;

readonly class BaseResource
{
    public function __construct()
    {
    }

    /**
     * @param non-empty-string $prefix
     * @param positive-int $suffixLength
     *
     * @return non-empty-lowercase-string
     */
    protected function generateId(string $prefix, int $suffixLength = 4): string
    {
        return strtolower(sprintf('%s_%s', $prefix, bin2hex(random_bytes($suffixLength))));
    }

    /**
     * @param non-empty-string $paths
     *
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://mock-llm.service/api/%s', ltrim(implode('/', $paths), '/'));
    }
}
