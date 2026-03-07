<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\File\Enum;

enum State: string
{
    case Active = 'ACTIVE';
    case Failed = 'FAILED';
    case Processing = 'PROCESSING';
    case Unspecified = 'STATE_UNSPECIFIED';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-uppercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @phpstan-assert-if-true self::Active $this
     */
    public function isActive(): bool
    {
        return self::Active === $this;
    }
}
