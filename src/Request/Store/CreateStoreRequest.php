<?php

namespace OneToMany\LlmSdk\Request\Store;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\RequiresVendorTrait;

use function trim;

class CreateStoreRequest
{
    use RequiresVendorTrait;

    /**
     * @var non-empty-string
     */
    private string $name;

    public function __construct(
        string|Vendor $vendor,
        ?string $name,
    ) {
        $this->usingVendor($vendor)->usingName($name);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws InvalidArgumentException when the trimmed name is empty
     */
    public function usingName(?string $name): static
    {
        if (!$name = trim((string) $name)) {
            throw new InvalidArgumentException('The name cannot be empty.');
        }

        $this->name = $name;

        return $this;
    }
}
