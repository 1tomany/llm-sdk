<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Batch;

final readonly class RequestCounts
{
    /**
     * @param non-negative-int $total
     * @param non-negative-int $completed
     * @param non-negative-int $failed
     */
    public function __construct(
        public int $total = 0,
        public int $completed = 0,
        public int $failed = 0,
    ) {
    }
}
