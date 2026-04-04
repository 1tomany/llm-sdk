<?php

namespace OneToMany\LlmSdk\Tests\Request\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Type\Query\Schema;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
final class CompileQueryRequestTest extends TestCase
{
    public function testUsingInstructionsRequiresNonEmbeddingModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertTrue($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model->getValue().'" does not support system prompts.');

        new CompileQueryRequest($model)->usingInstructions('You are a helpful large language model.');
    }

    public function testUsingSchemaRequiresNonEmbeddingModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertTrue($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model->getValue().'" does not support structured output.');

        new CompileQueryRequest($model)->usingSchema(['title' => 'JsonSchema']);
    }

    public function testUsingSchemaExtractsNameFromTitle(): void
    {
        // Arrange: Schema with title
        $schema = ['title' => 'Identify'];

        // Arrange: Compile query request
        $request = new CompileQueryRequest('mock');
        $this->assertNull($request->getSchema());

        // Act: Add the schema
        $request->usingSchema($schema);

        // Assert: The schema was added to the query
        $this->assertInstanceOf(Schema::class, $request->getSchema());

        // Assert: The schema name is equal to the title property of the schema
        $this->assertEquals($schema['title'], $request->getSchema()->getName());
    }

    public function testUsingDimensionsRequiresEmbeddingModel(): void
    {
        $model = Model::Mock;
        $this->assertFalse($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model->getValue().'" does not support changing the output dimensions.');

        new CompileQueryRequest($model)->usingDimensions(random_int(1, 1024));
    }
}
