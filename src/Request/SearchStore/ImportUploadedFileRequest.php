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
    private string $uri;
    private FileUri $fileUri;

    public function __construct(
        string|Vendor $vendor,
        ?string $uri,
        string|FileUri|null $fileUri,
    ) {
        $this->usingVendor($vendor)->usingUri($uri)->usingFileUri($fileUri);
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
