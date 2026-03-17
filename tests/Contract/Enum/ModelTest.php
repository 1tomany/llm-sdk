<?php

namespace OneToMany\LlmSdk\Tests\Contract\Enum;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function strtoupper;
use function uniqid;

#[Group('UnitTests')]
#[Group('ContractTests')]
#[Group('EnumTests')]
final class ModelTest extends TestCase
{
    #[DataProvider('providerEmptyModelName')]
    public function testCreatingModelRequiresNonEmptyModelName(?string $model): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model name cannot be empty.');

        Model::create($model);
    }

    /**
     * @return non-empty-list<non-empty-list<?string>>
     */
    public static function providerEmptyModelName(): array
    {
        $provider = [
            [null],
            [''],
            [' '],
            ["\n"],
            [" \t\n "],
        ];

        return $provider;
    }

    public function testCreatingModelRequiresValidName(): void
    {
        $model = uniqid('model_');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model.'" is not valid.');

        Model::create($model);
    }

    public function testCreatingModel(): void
    {
        /** @var Model $model */
        $model = new \Random\Randomizer()->shuffleArray(Model::cases())[0];

        $this->assertSame($model, Model::create(strtoupper($model->getValue())));
    }

    #[DataProvider('providerModelAndVendor')]
    public function testGettingVendor(Model $model, Vendor $vendor): void
    {
        $this->assertSame($vendor, $model->getVendor());
    }

    /**
     * @return non-empty-list<non-empty-list<Model|Vendor>>
     */
    public static function providerModelAndVendor(): array
    {
        $provider = [
            [Model::ClaudeOpus46, Vendor::Anthropic],
            [Model::ClaudeSonnet45, Vendor::Anthropic],
            [Model::ClaudeHaiku45, Vendor::Anthropic],
            [Model::Gemini31ProPreview, Vendor::Gemini],
            [Model::Gemini31ProPreview, Vendor::Gemini],
            [Model::Gemini31FlashLitePreview, Vendor::Gemini],
            [Model::Gemini3ProPreview, Vendor::Gemini],
            [Model::Gemini3FlashPreview, Vendor::Gemini],
            [Model::Gemini25Pro, Vendor::Gemini],
            [Model::Gemini25Flash, Vendor::Gemini],
            [Model::Gemini25FlashPreview, Vendor::Gemini],
            [Model::Gemini25FlashLite, Vendor::Gemini],
            [Model::Gemini25FlashLitePreview, Vendor::Gemini],
            [Model::GeminiEmbedding2Preview, Vendor::Gemini],
            [Model::GeminiEmbedding001, Vendor::Gemini],
            [Model::Mock, Vendor::Mock],
            [Model::MockEmbedding, Vendor::Mock],
            [Model::Gpt54Pro, Vendor::OpenAI],
            [Model::Gpt54, Vendor::OpenAI],
            [Model::Gpt52Pro, Vendor::OpenAI],
            [Model::Gpt52, Vendor::OpenAI],
            [Model::Gpt51, Vendor::OpenAI],
            [Model::Gpt5Pro, Vendor::OpenAI],
            [Model::Gpt5, Vendor::OpenAI],
            [Model::Gpt5Mini, Vendor::OpenAI],
            [Model::Gpt5Nano, Vendor::OpenAI],
            [Model::Gpt41, Vendor::OpenAI],
            [Model::GptEmbeddingAda002, Vendor::OpenAI],
            [Model::GptEmbedding3Small, Vendor::OpenAI],
            [Model::GptEmbedding3Large, Vendor::OpenAI],
        ];

        return $provider;
    }

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
            [Model::Mock, false],
            [Model::MockEmbedding, true],
            [Model::Gpt54Pro, false],
            [Model::Gpt54, false],
            [Model::Gpt52Pro, false],
            [Model::Gpt52, false],
            [Model::Gpt51, false],
            [Model::Gpt5Pro, false],
            [Model::Gpt5, false],
            [Model::Gpt5Mini, false],
            [Model::Gpt5Nano, false],
            [Model::Gpt41, false],
            [Model::GptEmbeddingAda002, true],
            [Model::GptEmbedding3Small, true],
            [Model::GptEmbedding3Large, true],
        ];

        return $provider;
    }
}
