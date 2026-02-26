<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Batch\Enum;

enum Status: string
{
    case Validating = 'validating';
    case Failed = 'failed';
    case InProgress = 'in_progress';
    case Finalizing = 'finalizing';
    case Completed = 'completed';
    case Expired = 'expired';
    case Cancelling = 'cancelling';
    case Cancelled = 'cancelled';

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
