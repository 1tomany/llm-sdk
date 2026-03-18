<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class DeleteFileResponse implements \JsonSerializable
{
    private Vendor $vendor;

    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        string|Vendor $vendor,
        private string $uri,
    ) {
        $this->vendor = Vendor::create($vendor);
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
     * @see \JsonSerializable
     *
     * @return array{
     *   vendor: non-empty-lowercase-string,
     *   uri: non-empty-string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'vendor' => $this->getVendor()->getValue(),
            'uri' => $this->getUri(),
        ];
    }
}
