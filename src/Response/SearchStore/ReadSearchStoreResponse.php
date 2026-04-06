<?php

namespace OneToMany\LlmSdk\Response\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Response\SearchStore\Trait\SearchStoreResponseTrait;

final readonly class ReadSearchStoreResponse
{
    use SearchStoreResponseTrait;

    /**
     * @param non-empty-string $uri
     * @param non-negative-int $size
     */
    public function __construct(
        private Vendor $vendor,
        private string $uri,
        private int $size,
    ) {
    }
}
