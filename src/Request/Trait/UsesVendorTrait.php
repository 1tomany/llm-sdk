<?php

namespace OneToMany\LlmSdk\Request\Trait;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

trait UsesVendorTrait
{
    private Vendor $vendor;

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    public function usingVendor(string|Vendor $vendor): static
    {
        $this->vendor = Vendor::create($vendor);

        return $this;
    }
}
