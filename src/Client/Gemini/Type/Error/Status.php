<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Error;

final readonly class Status
{
    /**
     * @param non-negative-int $code
     * @param non-empty-string $message
     */
    public function __construct(
        public int $code,
        public string $message,
    ) {
    }
}
