<?php

namespace OneToMany\LlmSdk\Contract\Enum;

enum Vendor: string
{
    case Anthropic = 'anthropic';
    case Gemini = 'gemini';
    case Mock = 'mock';
    case OpenAI = 'openai';

    public static function create(string|self|null $vendor): self
    {
        if ($vendor instanceof self) {
            return $vendor;
        }

        return self::tryFrom(\strtolower(\trim($vendor ?? ''))) ?? self::Mock;
    }

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
