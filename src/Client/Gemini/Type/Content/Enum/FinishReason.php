<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Content\Enum;

enum FinishReason: string
{
    case Other = 'OTHER';
    case Safety = 'SAFETY';
    case Stop = 'STOP';

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

    /**
     * @phpstan-assert-if-true self::Stop $this
     */
    public function isStop(): bool
    {
        return self::Stop === $this;
    }
}
