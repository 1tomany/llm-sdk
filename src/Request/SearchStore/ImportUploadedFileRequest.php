<?php

namespace OneToMany\LlmSdk\Request\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\UsesVendorTrait;
use OneToMany\LlmSdk\Request\Type\File\FileUri;

use function trim;

class ImportUploadedFileRequest
{
    use UsesVendorTrait;

    /**
     * @var non-empty-string
     */
    private string $searchStoreUri;
    private FileUri $fileUri;

    public function __construct(
        string|Vendor $vendor,
        ?string $searchStoreUri,
        string|FileUri|null $fileUri,
    ) {
        $this
            ->usingVendor($vendor)
            ->usingSearchStoreUri($searchStoreUri)
            ->usingFileUri($fileUri);
    }

    /**
     * @return non-empty-string
     */
    public function getSearchStoreUri(): string
    {
        return $this->searchStoreUri;
    }

    /**
     * @throws InvalidArgumentException when the trimmed search store URI is empty
     */
    public function usingSearchStoreUri(?string $searchStoreUri): static
    {
        if (!$searchStoreUri = trim((string) $searchStoreUri)) {
            throw new InvalidArgumentException('The search store URI cannot be empty.');
        }

        $this->searchStoreUri = $searchStoreUri;

        return $this;
    }

    public function getFileUri(): FileUri
    {
        return $this->fileUri;
    }

    public function usingFileUri(string|FileUri|null $fileUri): static
    {
        $this->fileUri = FileUri::create($fileUri);

        return $this;
    }
}
