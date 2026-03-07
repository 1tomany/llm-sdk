<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Output\Content\Enum;

enum Type: string
{
    case OutputText = 'output_text';
    case Refusal = 'refusal';

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
     * @phpstan-assert-if-true self::OutputText $this
     */
    public function isOutputText(): bool
    {
        return self::OutputText === $this;
    }

    /**
     * @phpstan-assert-if-true self::Refusal $this
     */
    public function isRefusal(): bool
    {
        return self::Refusal === $this;
    }
}
