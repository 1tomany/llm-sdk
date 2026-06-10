<?php

namespace OneToMany\LlmSdk\Client\Gemini\Response\File;

use OneToMany\LlmSdk\Contract\Client\Response\ClientResponseInterface;
use OneToMany\LlmSdk\Transfer\Record\File\FileRecord;

final readonly class UploadFileResponse implements ClientResponseInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $displayName
     * @param non-empty-string $mimeType
     * @param numeric-string $sizeBytes
     * @param non-empty-string $sha256Hash
     * @param non-empty-string $uri
     */
    public function __construct(
        public string $name,
        public string $displayName,
        public string $mimeType,
        public string $sizeBytes,
        public \DateTimeImmutable $createTime,
        public \DateTimeImmutable $updateTime,
        public \DateTimeImmutable $expirationTime,
        public string $sha256Hash,
        public string $uri,
        public string $state,
        public string $source,
    ) {
    }

    public function toRecord(): FileRecord
    {
        return new FileRecord($this->name, $this->expirationTime, $this->uri, $this->displayName);
    }
}
