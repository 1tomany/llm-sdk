<?php

namespace OneToMany\LlmSdk\Contract\Enum;

enum Vendor: string
{
    case Anthropic = 'anthropic';
    case Gemini = 'gemini';
    case Mock = 'mock';
    case OpenAI = 'openai';

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
