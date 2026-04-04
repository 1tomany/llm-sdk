<?php

namespace OneToMany\LlmSdk\Request\Store;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

class ImportFileRequest
{
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
        ?string $storeUri,
        ?string $fileUri,
        ?string $fileName,
    ) {
        $this
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
        if (!$storeUri = \trim((string) $storeUri)) {
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
        if (!$fileUri = \trim((string) $fileUri)) {
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
        $this->fileName = \trim((string) $fileName) ?: null;

        return $this;
    }
}
