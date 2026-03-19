<?php

namespace OneToMany\LlmSdk\Tests\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use const M_2_SQRTPI;
use const M_EULER;
use const M_PI;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('EmbeddingTests')]
final class CreateEmbeddingResponseTest extends TestCase
{
    public function testInvokingCreateEmbeddingResponseReturnsEmbedding(): void
    {
        $faker = \Faker\Factory::create();

        $response = new CreateEmbeddingResponse(Model::Mock, [
            M_PI, M_EULER, M_2_SQRTPI,
        ]);

        $this->assertSame($response->getEmbedding(), $response());
    }

    public function testConstructorRequiresNonEmptyEmbeddingVector(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The embedding vector cannot be empty.');

        new CreateEmbeddingResponse(Model::MockEmbedding, []); // @phpstan-ignore argument.type
    }
}
