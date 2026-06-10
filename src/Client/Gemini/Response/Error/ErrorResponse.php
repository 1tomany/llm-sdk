<?php

namespace OneToMany\LlmSdk\Client\Gemini\Response\Error;

use OneToMany\LlmSdk\Contract\Client\Response\ClientResponseInterface;

use function array_filter;
use function explode;
use function implode;
use function ltrim;

final readonly class ErrorResponse implements ClientResponseInterface
{
    public string $message;

    public function __construct(
        public int $code,
        string $message,
        public ?string $status = null,
    ) {
        // First, strip the asterisk Gemini
        // appends to the beginning of errors
        $message = trim(ltrim($message, '*'));

        // Next, remove any double spaces Gemini adds
        $messageBits = array_filter(explode(' ', $message));

        // Finally, compile the cleaned error message
        $this->message = trim(implode(' ', $messageBits));
    }
}
