<?php

namespace OneToMany\LlmSdk\Client\Anthropic\Type\File;

final readonly class DeletedFile
{
    /**
     * @param non-empty-string $id
     * @param 'file_deleted' $type
     */
    public function __construct(
        public string $id,
        public string $type,
    ) {
    }
}
