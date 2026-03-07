<?php

namespace OneToMany\LlmSdk\Resource\Mock\Trait;

trait GenerateIdTrait
{
    /**
     * @param non-empty-string $prefix
     * @param positive-int $suffixLength
     *
     * @return non-empty-lowercase-string
     */
    private function generateId(string $prefix, int $suffixLength = 4): string
    {
        return strtolower(sprintf('%s_%s', $prefix, bin2hex(random_bytes($suffixLength))));
    }
}
