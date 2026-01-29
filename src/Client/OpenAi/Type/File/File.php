<?php

namespace App\Prompt\Vendor\Model\Client\OpenAi\Type\File;

final readonly class File
{
    /**
     * @param non-empty-string $id
     * @param 'file' $object
     * @param non-negative-int $bytes
     * @param positive-int $created_at
     * @param positive-int|null $expires_at
     * @param non-empty-string $filename
     * @param non-empty-string $purpose
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $bytes,
        public int $created_at,
        public ?int $expires_at,
        public string $filename,
        public string $purpose,
    ) {
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return null !== $this->expires_at ? \DateTimeImmutable::createFromTimestamp($this->expires_at) : null;
    }
}
