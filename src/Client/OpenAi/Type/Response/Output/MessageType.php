<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Output;

use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;

final readonly class MessageType
{
    public function __construct(
        public string $type,
        public string $id,
        public Status $status,
    )
    {
	throw new \Exception('Not implemented');
    }
}
