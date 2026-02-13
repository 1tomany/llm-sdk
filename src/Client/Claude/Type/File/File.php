<?php

namespace OneToMany\AI\Client\Claude\Type\File;

final readonly class File
{
    /**
     * @param non-empty-string $id
     * @param 'file' $type
     * @param non-empty-string $filename
     * @param non-empty-lowercase-string $mime_type
     * @param non-negative-int $size_bytes
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $filename,
        public string $mime_type,
        public int $size_bytes,
        public \DateTimeImmutable $created_at,
        public bool $downloadable = false,
    ) {
    }
}
