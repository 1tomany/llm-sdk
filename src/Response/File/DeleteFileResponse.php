<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class DeleteFileResponse
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        private Vendor $vendor,
        private string $uri,
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
}
