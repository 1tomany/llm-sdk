<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response\Output\Content\Enum;

enum Type: string
{
    case OutputText = 'output_text';
    case Refusal = 'refusal';

    /**
     * @return 'OutputText'|'Refusal'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'output_text'|'refusal'
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
