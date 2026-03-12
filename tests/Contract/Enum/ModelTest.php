<?php

namespace OneToMany\LlmSdk\Tests\Contract\Enum;

use OneToMany\LlmSdk\Contract\Enum\Model;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ContractTests')]
#[Group('EnumTests')]
final class ModelTest extends TestCase
{
    #[DataProvider('providerModelAndIsEmbedding')]
    public function testIsEmbedding(Model $model, bool $isEmbedding): void
    {
        $this->assertSame($isEmbedding, $model->isEmbedding());
    }

    /**
     * @return non-empty-list<non-empty-list<bool|Model>>
     */
    public static function providerModelAndIsEmbedding(): array
    {
        $provider = [
            [Model::ClaudeOpus46, false],
            [Model::ClaudeSonnet45, false],
            [Model::ClaudeHaiku45, false],
            [Model::Gemini31ProPreview, false],
            [Model::Gemini31ProPreview, false],
            [Model::Gemini31FlashLitePreview, false],
            [Model::Gemini3ProPreview, false],
            [Model::Gemini3FlashPreview, false],
            [Model::Gemini25Pro, false],
            [Model::Gemini25Flash, false],
            [Model::Gemini25FlashPreview, false],
            [Model::Gemini25FlashLite, false],
            [Model::Gemini25FlashLitePreview, false],
            [Model::GeminiEmbedding2Preview, true],
            [Model::GeminiEmbedding001, true],
        ];

        return $provider;
    }
}
