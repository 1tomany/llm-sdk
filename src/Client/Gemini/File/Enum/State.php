<?php

namespace OneToMany\AI\Client\Gemini\File\Enum;

enum State: string
{
    case Active = 'ACTIVE';
    case Failed = 'FAILED';
    case Processing = 'PROCESSING';
    case Unspecified = 'STATE_UNSPECIFIED';

    /**
     * @return 'Active'|'Failed'|'Processing'|'Unspecified'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'ACTIVE'|'FAILED'|'PROCESSING'|'STATE_UNSPECIFIED'
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
