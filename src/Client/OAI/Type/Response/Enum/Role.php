<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Response\Enum;

enum Role: string
{
    case Assistant = 'assistant';
    case Developer = 'developer';
    case User = 'user';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
