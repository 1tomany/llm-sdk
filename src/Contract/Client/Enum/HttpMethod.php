<?php

namespace OneToMany\LlmSdk\Contract\Client\Enum;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case DELETE = 'DELETE';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
