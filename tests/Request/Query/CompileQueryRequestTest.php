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

        new CompileQueryRequest($model)->usingSchema(null, ['title' => 'JsonSchema']);
    }

    public function testUsingSchemaExtractsNameFromTitle(): void
    {
        // Arrange: Schema with title
        $schema = ['title' => 'Identify'];

        // Arrange: Compile query request
        $request = new CompileQueryRequest();
        $this->assertNull($request->getSchema());

        // Act: Add the schema to the request
        $request->usingSchema(null, $schema);

        // Assert: The schema was added to the request
        $this->assertInstanceOf(Schema::class, $request->getSchema());

        // Assert: The schema name is equal to the title property of the schema
        $this->assertEquals($schema['title'], $request->getSchema()->getName());
    }

    public function testUsingSchemaSetsDefaultNameWhenTitleIsMissing(): void
    {
        // Arrange: Compile query request
        $request = new CompileQueryRequest();
        $this->assertNull($request->getSchema());

        // Act: Add a nameless schema to the request
        $request->usingSchema(null, ['required' => ['id']]);

        // Assert: The schema was added to the request
        $this->assertInstanceOf(Schema::class, $request->getSchema());

        // Assert: The default schema name is used
        $this->assertEquals('JsonSchema', $request->getSchema()->getName());
    }

    public function testUsingSchemaWithName(): void
    {
        // Arrange: Schema name
        $schemaName = 'Identity';

        // Arrange: JSON schema
        $schema = [
            'title' => 'ID',
            'required' => [
                'name',
                'age',
                'weight',
                'height',
            ],
        ];

        // Assert: Schema name and schema title are not equal
        $this->assertNotEquals($schemaName, $schema['title']);

        // Arrange: Compile query request
        $request = new CompileQueryRequest();
        $this->assertNull($request->getSchema());

        // Act: Add the schema to the request
        $request->usingSchema($schemaName, $schema);

        // Assert: The schema was added to the request
        $this->assertInstanceOf(Schema::class, $request->getSchema());

        // Assert: The original schema name is used
        $this->assertEquals($schemaName, $request->getSchema()->getName());
    }

    public function testUsingDimensionsRequiresEmbeddingModel(): void
    {
        $model = Model::Mock;
        $this->assertFalse($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock" does not support changing the output dimensions.');

        new CompileQueryRequest($model)->usingDimensions(random_int(1, 1024));
    }
}
