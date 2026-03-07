<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Error;

use function array_filter;
use function explode;
use function implode;

final class Error
{
    private string $cleanedMessage = null;

    public function __construct(
        public readonly int $code,
        public readonly string $message,
        public readonly ?string $status = null,
    ) {
    }

    public function getMessage(): string
    {
        if (null === $this->cleanedMessage) {
            // First, strip the asterisk Gemini sometimes
            // appends to the beginning of error messages
            $message = trim(ltrim($this->message, '*'));

            // Next, remove any double spaces Gemini adds
            $messageBits = array_filter(explode(' ', $message));

            // Finally, compile the cleaned error message
            $this->cleanedMessage = trim(implode(' ', $messageBits));
        }

        return $this->cleanedMessage;
    }
}
