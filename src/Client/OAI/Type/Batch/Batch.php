<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Batch;

use OneToMany\LlmSdk\Client\OpenAI\Type\Batch\Enum\Status;
use OneToMany\LlmSdk\Client\OpenAI\Type\Error\Error;
use OneToMany\LlmSdk\Client\OpenAI\Type\Usage\Usage;

final readonly class Batch
{
    /**
     * @param non-empty-string $id
     * @param 'batch' $object
     * @param non-empty-string $endpoint
     * @param ?non-empty-lowercase-string $model
     * @param ?list<Error> $errors
     * @param non-empty-string $input_file_id
     * @param non-empty-string $completion_window
     * @param ?non-empty-string $output_file_id
     * @param ?non-empty-string $error_file_id
     * @param non-negative-int $created_at
     * @param ?non-negative-int $in_progress_at
     * @param ?non-negative-int $expires_at
     * @param ?non-negative-int $finalizing_at
     * @param ?non-negative-int $completed_at
     * @param ?non-negative-int $failed_at
     * @param ?non-negative-int $expired_at
     * @param ?non-negative-int $cancelling_at
     * @param ?non-negative-int $cancelled_at
     * @param ?array<string, mixed> $metadata
     */
    public function __construct(
        public string $id,
        public string $object,
        public string $endpoint,
        public ?string $model,
        public ?array $errors,
        public string $input_file_id,
        public string $completion_window,
        public Status $status,
        public ?string $output_file_id,
        public ?string $error_file_id,
        public int $created_at,
        public ?int $in_progress_at,
        public ?int $expires_at,
        public ?int $finalizing_at,
        public ?int $completed_at,
        public ?int $failed_at,
        public ?int $expired_at,
        public ?int $cancelling_at,
        public ?int $cancelled_at,
        public RequestCounts $request_counts = new RequestCounts(),
        public Usage $usage = new Usage(),
        public ?array $metadata = null,
    ) {
    }
}
