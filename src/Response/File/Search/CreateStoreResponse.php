<?php

namespace OneToMany\LlmSdk\Response\File\Search;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Response\File\BaseResponse;

final readonly class CreateStoreResponse extends BaseResponse implements \JsonSerializable
{
    /**
     * @param non-empty-string $uri
     * @param non-negative-int $size
     */
    public function __construct(
        string|Vendor $vendor,
        private string $uri,
        private int $size,
    ) {
        parent::__construct($vendor);
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
