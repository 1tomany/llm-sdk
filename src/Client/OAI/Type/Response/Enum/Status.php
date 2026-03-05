<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Response\Enum;

enum Status: string
{
    case Calling = 'calling';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case Failed = 'failed';
    case Incomplete = 'incomplete';
    case InProgress = 'in_progress';
    case Interpreting = 'interpreting';
    case Queued = 'queued';

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
     * @phpstan-assert-if-true self::Completed $this
     */
    public function isCompleted(): bool
    {
        return self::Completed === $this;
    }
}
