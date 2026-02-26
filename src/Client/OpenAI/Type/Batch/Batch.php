<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Batch;

use OneToMany\LlmSdk\Client\OpenAI\Type\Batch\Enum\Status;

final readonly class Batch
{
    /**
     * @param non-empty-string $id
     * @param 'batch' $object
     * @param non-empty-string $endpoint
     * @param ?non-empty-string $output_file_id
     */
    public function __construct(
        public string $id,
        public string $object,
        public string $endpoint,
        public Status $status = Status::Validating,
        public ?string $output_file_id = null,
    ) {
    }
}
