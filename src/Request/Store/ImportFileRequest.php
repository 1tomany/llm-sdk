<?php

namespace OneToMany\LlmSdk\Request\Store;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\UsesVendorTrait;

use function trim;

class ImportFileRequest
{
    use UsesVendorTrait;

    /**
     * @var non-empty-string
     */
    private string $storeUri;

    /**
     * @var non-empty-string
     */
    private string $fileUri;

    /**
     * @var ?non-empty-string
     */
    private ?string $fileName;

    public function __construct(
        string|Vendor $vendor,
        ?string $storeUri,
        ?string $fileUri,
        ?string $fileName = null,
    ) {
        $this
            ->usingVendor($vendor)
            ->usingStoreUri($storeUri)
            ->usingFileUri($fileUri)
            ->usingFileName($fileName);
    }

    /**
     * @return non-empty-string
     */
    public function getStoreUri(): string
    {
        return $this->storeUri;
    }

    /**
     * @throws InvalidArgumentException when the trimmed store URI is empty
     */
    public function usingStoreUri(?string $storeUri): static
    {
        if (!$storeUri = trim((string) $storeUri)) {
            throw new InvalidArgumentException('The store URI cannot be empty.');
        }

        $this->storeUri = $storeUri;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getFileUri(): string
    {
        return $this->fileUri;
    }

    /**
     * @throws InvalidArgumentException when the trimmed file URI is empty
     */
    public function usingFileUri(?string $fileUri): static
    {
        if (!$fileUri = trim((string) $fileUri)) {
            throw new InvalidArgumentException('The file URI cannot be empty.');
        }

        $this->fileUri = $fileUri;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function usingFileName(?string $fileName): static
    {
        $this->fileName = trim((string) $fileName) ?: null;

        return $this;
    }
}
