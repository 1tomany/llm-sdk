<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\File;

final readonly class DeletedFile
{
    /**
     * @param 'file' $object
     * @param non-empty-string $id
     */
    public function __construct(
        public string $object,
        public string $id,
        public bool $deleted,
    ) {
    }
}
