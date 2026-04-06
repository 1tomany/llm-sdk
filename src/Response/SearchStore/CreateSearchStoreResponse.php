<?php

namespace OneToMany\LlmSdk\Response\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class CreateSearchStoreResponse implements \JsonSerializable
{
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

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return non-negative-int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   vendor: non-empty-lowercase-string,
     *   uri: non-empty-string,
     *   size: non-negative-int,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'vendor' => $this->getVendor()->getValue(),
            'uri' => $this->getUri(),
            'size' => $this->getSize(),
        ];
    }
}
