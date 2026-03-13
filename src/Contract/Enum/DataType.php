<?php

namespace OneToMany\LlmSdk\Contract\Enum;

enum DataType
{
    case Audio;
    case Image;
    case Pdf;
    case Text;
    case Video;

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @phpstan-assert-if-true self::Audio $this
     */
    public function isAudio(): bool
    {
        return self::Audio === $this;
    }
}
