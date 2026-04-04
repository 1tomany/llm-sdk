<?php

namespace OneToMany\LlmSdk\Request\Store;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class CreateStoreRequest
{
    private Vendor $vendor;

    /**
     * @param non-empty-string $name
     *
     * @return void
     */
    public function __construct(
        string|Vendor $vendor,
        private string $name,
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
    public function getName(): string
    {
        return $this->name;
    }
}
