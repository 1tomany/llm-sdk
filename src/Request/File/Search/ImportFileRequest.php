<?php

namespace OneToMany\LlmSdk\Request\File\Search;

final readonly class ImportFileRequest
{
    /**
     * @param non-empty-string $storeUri
     * @param non-empty-string $fileUri
     * @param ?non-empty-string $fileName
     */
    public function __construct(
        private string $storeUri,
        private string $fileUri,
        private ?string $fileName,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getStoreUri(): string
    {
        return $this->storeUri;
    }

    /**
     * @return non-empty-string
     */
    public function getFileUri(): string
    {
        return $this->fileUri;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }
}
