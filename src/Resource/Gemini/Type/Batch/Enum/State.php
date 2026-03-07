<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch\Enum;

enum State: string
{
    case Unspecified = 'BATCH_STATE_UNSPECIFIED';
    case Pending = 'BATCH_STATE_PENDING';
    case Running = 'BATCH_STATE_RUNNING';
    case Succeeded = 'BATCH_STATE_SUCCEEDED';
    case Failed = 'BATCH_STATE_FAILED';
    case Cancelled = 'BATCH_STATE_CANCELLED';
    case Expired = 'BATCH_STATE_EXPIRED';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
