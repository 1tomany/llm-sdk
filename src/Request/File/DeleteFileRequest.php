<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\RequiresVendorTrait;

use function trim;

class DeleteFileRequest
{
    use RequiresVendorTrait;

    /**
     * @var non-empty-string
     */
    private string $uri;

    public function __construct(
        string|Vendor $vendor,
        ?string $uri,
    ) {
        $this->usingVendor($vendor)->usingUri($uri);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @throws InvalidArgumentException when the trimmed URI is empty
     */
    public function usingUri(?string $uri): static
    {
        if (!$uri = trim((string) $uri)) {
            throw new InvalidArgumentException('The URI cannot be empty.');
        }

        $this->uri = $uri;

        return $this;
    }
}
