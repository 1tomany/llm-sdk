<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\File\Enum;

use function strtolower;
use function trim;

enum Purpose: string
{
    case Assistants = 'assistants';
    case Batch = 'batch';
    case Evals = 'evals';
    case FineTune = 'fine-tune';
    case UserData = 'user_data';
    case Vision = 'vision';

    public static function create(?string $purpose): self
    {
        return self::tryFrom(strtolower(trim($purpose ?? ''))) ?: self::UserData;
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
