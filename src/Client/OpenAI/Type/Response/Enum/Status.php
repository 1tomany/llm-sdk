<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response\Enum;

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
     * @return 'Calling'|'Cancelled'|'Completed'|'Failed'|'Incomplete'|'InProgress'|'Interpreting'|'Queued'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'calling'|'cancelled'|'completed'|'failed'|'incomplete'|'in_progress'|'interpreting'|'queued'
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
