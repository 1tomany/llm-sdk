<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class DeleteFileRequest
{
    private Vendor $vendor;

    /**
     * @param Vendor $vendor
     * @param non-empty-string $uri
     *
     * @return void
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
