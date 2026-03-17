<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use function bin2hex;
use function random_bytes;
use function sprintf;
use function strtolower;

readonly class BaseResource
{
    protected \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
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
}
