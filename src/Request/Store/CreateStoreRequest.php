<?php

namespace OneToMany\LlmSdk\Request\Store;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Request\Trait\RequiresVendorTrait;

final readonly class CreateStoreRequest
{
    use RequiresVendorTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string|Vendor $vendor,
        private readonly string $name,
    ) {
        $this->vendor = Vendor::create($vendor);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
