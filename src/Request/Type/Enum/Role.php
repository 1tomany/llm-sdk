<?php

namespace OneToMany\LlmSdk\Request\Type\Enum;

enum Role: string
{
    case System = 'system';
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

    /**
     * @phpstan-assert-if-true self::System $this
     */
    public function isSystem(): bool
    {
        return self::System === $this;
    }

    /**
     * @phpstan-assert-if-true self::User $this
     */
    public function isUser(): bool
    {
        return self::User === $this;
    }
}
