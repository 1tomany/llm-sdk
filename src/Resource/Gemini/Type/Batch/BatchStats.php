<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch;

final readonly class BatchStats
{
    /**
     * @param numeric-string $requestCount
     * @param numeric-string $successfulRequestCount
     * @param numeric-string $failedRequestCount
     * @param numeric-string $pendingRequestCount
     */
    public function __construct(
        public string $requestCount = '0',
        public string $successfulRequestCount = '0',
        public string $failedRequestCount = '0',
        public string $pendingRequestCount = '0',
    ) {
    }
}
