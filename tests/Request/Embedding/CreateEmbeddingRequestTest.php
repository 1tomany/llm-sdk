<?php

namespace OneToMany\LlmSdk\Tests\Request\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('EmbeddingTests')]
final class CreateEmbeddingRequestTest extends TestCase
{
    public function testConstructorRequiresEmbeddingModel(): void
    {
        $model = Model::Mock;
        $this->assertFalse($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model->getValue().'" is not an embedding model.');

        new CreateEmbeddingRequest($model, 'https://mock-llm.service/embeddings', []);
    }
}
