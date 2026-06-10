<?php

namespace OneToMany\LlmSdk\Transfer\Record\File;

use OneToMany\LlmSdk\Contract\Transfer\Record\RecordInterface;

final readonly class FileRecord implements RecordInterface
{
    public function __construct(
        public string $id,
        public ?\DateTimeImmutable $expiresAt = null,
        public ?string $uri = null,
        public ?string $name = null,
        public ?string $purpose = null,
    ) {
    }
}
