<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response\Enum;

enum Role: string
{
    case Assistant = 'assistant';
    case Developer = 'developer';
    case User = 'user';

    /**
     * @return 'Assistant'|'Developer'|'User'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'assistant'|'developer'|'user'
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
