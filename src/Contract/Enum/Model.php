<?php

namespace OneToMany\AI\Contract\Enum;

enum Model: string
{
    // Gemini
    case Gemini3ProPreview = 'gemini-3-pro-preview';
    case Gemini3FlashPreview = 'gemini-3-flash-preview';
    case Gemini25Pro = 'gemini-2.5-pro';
    case Gemini25Flash = 'gemini-2.5-flash';
    case Gemini25FlashPreview = 'gemini-2.5-flash-preview-09-2025';
    case Gemini25FlashLite = 'gemini-2.5-flash-lite';
    case Gemini25FlashLitePreview = 'gemini-2.5-flash-lite-preview-09-2025';

    // OpenAI
    case Gpt52Pro = 'gpt-5.2-pro';
    case Gpt52 = 'gpt-5.2';
    case Gpt51 = 'gpt-5.1';
    case Gpt5Pro = 'gpt-5-pro';
    case Gpt5 = 'gpt-5';
    case Gpt5Mini = 'gpt-5-mini';
    case Gpt5Nano = 'gpt-5-nano';
    case Gpt41 = 'gpt-4.1';

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
     * @return non-empty-lowercase-string
     */
    public function getId(): string
    {
        $id = match($this) {
            self::Gpt52Pro => 'gpt-5.2-pro-2025-12-11',
            self::Gpt52 => 'gpt-5.2-2025-12-11',
            self::Gpt51 => 'gpt-5.1-2025-11-13',
            self::Gpt5Pro => 'gpt-5-pro-2025-10-06',
            self::Gpt5 => 'gpt-5-2025-08-07',
            self::Gpt5Mini => 'gpt-5-mini-2025-08-07',
            self::Gpt5Nano => 'gpt-5-nano-2025-08-07',
            default => $this->getValue(),
        };

        return $id;
    }
}
