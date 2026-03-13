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

    /**
     * @phpstan-assert-if-true self::Image $this
     */
    public function isImage(): bool
    {
        return self::Image === $this;
    }

    /**
     * @phpstan-assert-if-true self::Pdf $this
     */
    public function isPdf(): bool
    {
        return self::Pdf === $this;
    }

    /**
     * @phpstan-assert-if-true self::Text $this
     */
    public function isText(): bool
    {
        return self::Text === $this;
    }

    /**
     * @phpstan-assert-if-true self::Video $this
     */
    public function isVideo(): bool
    {
        return self::Video === $this;
    }
}
