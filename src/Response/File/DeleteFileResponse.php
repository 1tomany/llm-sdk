<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class DeleteFileResponse
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
}
