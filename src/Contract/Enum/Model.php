<?php

namespace OneToMany\LlmSdk\Contract\Enum;

use function in_array;
use function strtolower;
use function trim;

enum Model: string
{
    // Anthropic
    case ClaudeOpus46 = 'claude-opus-4-6';
    case ClaudeSonnet45 = 'claude-sonnet-4-5';
    case ClaudeHaiku45 = 'claude-haiku-4-5';

    // Gemini
    case Gemini31ProPreview = 'gemini-3.1-pro-preview';
    case Gemini31FlashLitePreview = 'gemini-3.1-flash-lite-preview';
    case Gemini3ProPreview = 'gemini-3-pro-preview';
    case Gemini3FlashPreview = 'gemini-3-flash-preview';
    case Gemini25Pro = 'gemini-2.5-pro';
    case Gemini25Flash = 'gemini-2.5-flash';
    case Gemini25FlashPreview = 'gemini-2.5-flash-preview';
    case Gemini25FlashLite = 'gemini-2.5-flash-lite';
    case Gemini25FlashLitePreview = 'gemini-2.5-flash-lite-preview';
    case GeminiEmbedding2Preview = 'gemini-embedding-2-preview';
    case GeminiEmbedding001 = 'gemini-embedding-001';

    // Mock
    case Mock = 'mock';
    case MockEmbedding = 'mock-embedding';

    // OpenAI
    case Gpt54Pro = 'gpt-5.4-pro';
    case Gpt54 = 'gpt-5.4';
    case Gpt52Pro = 'gpt-5.2-pro';
    case Gpt52 = 'gpt-5.2';
    case Gpt51 = 'gpt-5.1';
    case Gpt5Pro = 'gpt-5-pro';
    case Gpt5 = 'gpt-5';
    case Gpt5Mini = 'gpt-5-mini';
    case Gpt5Nano = 'gpt-5-nano';
    case Gpt41 = 'gpt-4.1';
    case GptEmbeddingAda002 = 'gpt-embedding-ada-002';
    case GptEmbedding3Small = 'gpt-embedding-3-small';
    case GptEmbedding3Large = 'gpt-embedding-3-large';

    public static function create(string|self|null $model): self
    {
        if ($model instanceof self) {
            return $model;
        }

        return self::tryFrom(strtolower(trim($model ?? ''))) ?? self::Mock;
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

    /**
     * @return non-empty-lowercase-string
     */
    public function getId(): string
    {
        $id = match ($this) {
            // Anthropic
            self::ClaudeOpus46 => 'claude-opus-4-6',
            self::ClaudeSonnet45 => 'claude-sonnet-4-5-20250929',
            self::ClaudeHaiku45 => 'claude-haiku-4-5-20251001',

            // Gemini
            self::Gemini31ProPreview => 'gemini-3.1-pro-preview',
            self::Gemini31FlashLitePreview => 'gemini-3.1-flash-lite-preview',
            self::Gemini3ProPreview => 'gemini-3-pro-preview',
            self::Gemini3FlashPreview => 'gemini-3-flash-preview',
            self::Gemini25Pro => 'gemini-2.5-pro',
            self::Gemini25Flash => 'gemini-2.5-flash',
            self::Gemini25FlashPreview => 'gemini-2.5-flash-preview-09-2025',
            self::Gemini25FlashLite => 'gemini-2.5-flash-lite',
            self::Gemini25FlashLitePreview => 'gemini-2.5-flash-lite-preview-09-2025',
            self::GeminiEmbedding2Preview => 'gemini-embedding-2-preview',
            self::GeminiEmbedding001 => 'gemini-embedding-001',

            // Mock
            self::Mock => 'mock',
            self::MockEmbedding => 'mock-embedding',

            // OpenAI
            self::Gpt54Pro => 'gpt-5.4-pro-2026-03-05',
            self::Gpt54 => 'gpt-5.4-2026-03-05',
            self::Gpt52Pro => 'gpt-5.2-pro-2025-12-11',
            self::Gpt52 => 'gpt-5.2-2025-12-11',
            self::Gpt51 => 'gpt-5.1-2025-11-13',
            self::Gpt5Pro => 'gpt-5-pro-2025-10-06',
            self::Gpt5 => 'gpt-5-2025-08-07',
            self::Gpt5Mini => 'gpt-5-mini-2025-08-07',
            self::Gpt5Nano => 'gpt-5-nano-2025-08-07',
            self::Gpt41 => 'gpt-4.1-2025-04-14',
            self::GptEmbeddingAda002 => 'text-embedding-ada-002',
            self::GptEmbedding3Small => 'text-embedding-3-small',
            self::GptEmbedding3Large => 'text-embedding-3-large',
        };

        return $id;
    }

    public function getVendor(): Vendor
    {
        $vendor = match ($this) {
            // Anthropic
            self::ClaudeOpus46 => Vendor::Anthropic,
            self::ClaudeSonnet45 => Vendor::Anthropic,
            self::ClaudeHaiku45 => Vendor::Anthropic,

            // Gemini
            self::Gemini31ProPreview => Vendor::Gemini,
            self::Gemini31FlashLitePreview => Vendor::Gemini,
            self::Gemini3ProPreview => Vendor::Gemini,
            self::Gemini3FlashPreview => Vendor::Gemini,
            self::Gemini25Pro => Vendor::Gemini,
            self::Gemini25Flash => Vendor::Gemini,
            self::Gemini25FlashPreview => Vendor::Gemini,
            self::Gemini25FlashLite => Vendor::Gemini,
            self::Gemini25FlashLitePreview => Vendor::Gemini,
            self::GeminiEmbedding2Preview => Vendor::Gemini,
            self::GeminiEmbedding001 => Vendor::Gemini,

            // Mock
            self::Mock => Vendor::Mock,
            self::MockEmbedding => Vendor::Mock,

            // OpenAI
            self::Gpt54Pro => Vendor::OpenAI,
            self::Gpt54 => Vendor::OpenAI,
            self::Gpt52Pro => Vendor::OpenAI,
            self::Gpt52 => Vendor::OpenAI,
            self::Gpt51 => Vendor::OpenAI,
            self::Gpt5Pro => Vendor::OpenAI,
            self::Gpt5 => Vendor::OpenAI,
            self::Gpt5Mini => Vendor::OpenAI,
            self::Gpt5Nano => Vendor::OpenAI,
            self::Gpt41 => Vendor::OpenAI,
            self::GptEmbeddingAda002 => Vendor::OpenAI,
            self::GptEmbedding3Small => Vendor::OpenAI,
            self::GptEmbedding3Large => Vendor::OpenAI,
        };

        return $vendor;
    }

    /**
     * @phpstan-assert-if-true self::GeminiEmbedding2Preview|self::GeminiEmbedding001|self::MockEmbedding|self::GptEmbeddingAda002|self::GptEmbedding3Small|self::GptEmbedding3Large $this
     */
    public function isEmbedding(): bool
    {
        return in_array($this, [
            self::GeminiEmbedding2Preview,
            self::GeminiEmbedding001,
            self::MockEmbedding,
            self::GptEmbeddingAda002,
            self::GptEmbedding3Small,
            self::GptEmbedding3Large,
        ]);
    }

    /**
     * @phpstan-assert-if-false self::GeminiEmbedding001|self::GptEmbeddingAda002|self::GptEmbedding3Small|self::GptEmbedding3Large $this
     */
    public function isMultiModal(): bool
    {
        return !in_array($this, [
            self::GeminiEmbedding001,
            self::GptEmbeddingAda002,
            self::GptEmbedding3Small,
            self::GptEmbedding3Large,
        ]);
    }
}
