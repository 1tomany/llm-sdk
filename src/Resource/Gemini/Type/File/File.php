<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\File;

use OneToMany\LlmSdk\Resource\Gemini\Type\File\Enum\Source;
use OneToMany\LlmSdk\Resource\Gemini\Type\File\Enum\State;

final readonly class File
{
    /**
     * @param non-empty-string $name
     * @param non-empty-lowercase-string $mimeType
     * @param numeric-string $sizeBytes
     * @param non-empty-string $sha256Hash
     * @param non-empty-string $uri
     */
    public function __construct(
        public string $name,
        public string $mimeType,
        public string $sizeBytes,
        public \DateTimeImmutable $createTime,
        public \DateTimeImmutable $updateTime,
        public \DateTimeImmutable $expirationTime,
        public string $sha256Hash,
        public string $uri,
        public State $state = State::Active,
        public Source $source = Source::Uploaded,
    ) {
    }
}
