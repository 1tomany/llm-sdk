<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

class BaseRequest
{
    private Vendor $vendor = Vendor::Mock;

    public function __construct(
        string|Vendor|null $vendor = Vendor::Mock,
    ) {
        $this->forVendor($vendor);
    }

    public function forVendor(string|Vendor|null $vendor): static
    {
        $this->vendor = Vendor::create($vendor);

        return $this;
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }
}
