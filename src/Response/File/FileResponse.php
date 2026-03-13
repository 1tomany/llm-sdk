<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

readonly class FileResponse
{
    public function __construct(
        private Vendor $vendor,
    ) {
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }
}
