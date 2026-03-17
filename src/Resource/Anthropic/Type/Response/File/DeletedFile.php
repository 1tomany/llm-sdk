<?php

namespace OneToMany\LlmSdk\Resource\Anthropic\Type\Response\File;

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
