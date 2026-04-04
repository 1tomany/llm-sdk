<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore;

final readonly class FileSearchStore
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $displayName
     * @param ?numeric-string $activeDocumentsCount
     * @param ?numeric-string $pendingDocumentsCount
     * @param ?numeric-string $failedDocumentsCount
     * @param ?numeric-string $sizeBytes
     */
    public function __construct(
        public string $name,
        public string $displayName,
        public \DateTimeImmutable $createTime,
        public \DateTimeImmutable $updateTime,
        public ?string $activeDocumentsCount = null,
        public ?string $pendingDocumentsCount = null,
        public ?string $failedDocumentsCount = null,
        public ?string $sizeBytes = null,
    )
    {
    }

    /**
     * @return non-negative-int
     */
    public function getSize(): int
    {
        return \max(0, (int) $this->sizeBytes);
    }
}
