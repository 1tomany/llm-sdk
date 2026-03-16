<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\Enum;

enum Purpose: string
{
    case Assistants = 'assistants';
    case Batch = 'batch';
    case Evals = 'evals';
    case FineTune = 'fine-tune';
    case UserData = 'user_data';
    case Vision = 'vision';

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
