<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

readonly class BaseResponse
{
    private Vendor $vendor;

    public function __construct(
        string|Vendor $vendor,
    )
    {
        $this->vendor = Vendor::create($vendor);
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }
}
