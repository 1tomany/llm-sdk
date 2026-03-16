<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File;

final readonly class DeletedFile
{
    /**
     * @param 'file' $object
     * @param non-empty-string $id
     */
    public function __construct(
        public string $object,
        public bool $deleted,
        public string $id,
    ) {
    }
}
