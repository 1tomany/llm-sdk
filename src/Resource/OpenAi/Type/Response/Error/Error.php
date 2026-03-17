<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Error;

use function rtrim;
use function sprintf;
use function trim;

final readonly class Error
{
    /**
     * @var non-empty-string
     */
    public string $message;

    /**
     * @param non-empty-string $message
     * @param ?non-empty-string $type
     * @param ?non-empty-string $param
     * @param ?non-empty-string $code
     */
    public function __construct(
        string $message,
        public ?string $type = null,
        public ?string $param = null,
        public ?string $code = null,
    ) {
        $this->message = sprintf('%s.', trim(rtrim($message, '.')));
    }
}
