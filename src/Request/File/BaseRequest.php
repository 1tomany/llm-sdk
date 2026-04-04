<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

class BaseRequest
{
    private readonly Vendor $vendor;

    public function __construct(
        string|Vendor $vendor,
    ) {
        $this->vendor = Vendor::create($vendor);
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }
}
