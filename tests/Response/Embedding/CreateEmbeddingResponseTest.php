<?php

namespace OneToMany\LlmSdk\Tests\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('EmbeddingTests')]
final class CreateEmbeddingResponseTest extends TestCase
{
    public function testConstructorRequiresNonEmptyEmbeddingVector(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The embedding vector cannot be empty.');

        new CreateEmbeddingResponse(Model::MockEmbedding, []); // @phpstan-ignore argument.type
    }
}
