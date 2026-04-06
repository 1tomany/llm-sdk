<?php

namespace OneToMany\LlmSdk\Request\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\UsesVendorTrait;

use function trim;

class CreateSearchStoreRequest
{
    use UsesVendorTrait;

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
     * @throws InvalidArgumentException when the trimmed search store name is empty
     */
    public function usingName(?string $name): static
    {
        if (!$name = trim((string) $name)) {
            throw new InvalidArgumentException('The search store name cannot be empty.');
        }

        $this->name = $name;

        return $this;
    }
}
