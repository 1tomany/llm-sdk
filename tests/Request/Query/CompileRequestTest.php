<?php

namespace OneToMany\LlmSdk\Tests\Request\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\Type\Schema;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
final class CompileRequestTest extends TestCase
{
    public function testUsingDimensionsRequiresEmbeddingModel(): void
    {
        $model = Model::Mock;
        $this->assertFalse($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock" does not support changing the output dimensions.');

        new CompileQueryRequest($model)->usingDimensions(random_int(1, 1024));
    }

    public function testUsingInstructionsRequiresNonEmbeddingModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertTrue($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock-embedding" does not support system instructions.');

        new CompileQueryRequest($model)->usingInstructions('You are a helpful large language model.');
    }

    public function testUsingSchemaRequiresNonEmbeddingModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertTrue($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock-embedding" does not support structured output.');

        new CompileQueryRequest($model)->usingSchema(null, ['title' => 'JsonSchema']);
    }

    public function testUsingSchemaExtractsNameFromTitle(): void
    {
        // Arrange: JSON Schema
        $jsonSchema = ['title' => 'Identify'];

        // Arrange: Compile query request
        $compileRequest = new CompileQueryRequest();
        $this->assertNull($compileRequest->getSchema());

        // Act: Add the JSON schema to the request
        $compileRequest->usingSchema(null, $jsonSchema);
        $this->assertNotNull($compileRequest->getSchema());

        // Assert: The JSON schema was added
        $input = $compileRequest->getSchema();

        // Assert: The title of the JSON schema is used as the name
        $this->assertInstanceOf(Schema::class, $input);
        $this->assertEquals($jsonSchema['title'], $input->getName());
    }

    public function testUsingSchemaSetsDefaultNameWhenTitleIsMissing(): void
    {
        $compileRequest = new CompileQueryRequest();
        $this->assertNull($compileRequest->getSchema());

        $compileRequest->usingSchema(null, ['required' => ['id']]);
        $this->assertNotNull($compileRequest->getSchema());

        // Assert: The JSON schema was added
        $input = $compileRequest->getSchema();

        $this->assertInstanceOf(Schema::class, $input);
        $this->assertEquals('JsonSchema', $input->getName());
    }

    public function testUsingSchemaWithName(): void
    {
        // Arrange: Schema name
        $name = 'Identity';

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
        $this->assertNotEquals($name, $schema['title']);

        // Arrange: Compile query request
        $compileRequest = new CompileQueryRequest();
        $this->assertNull($compileRequest->getSchema());

        // Act: Add the JSON schema to the request
        $compileRequest->usingSchema($name, $schema);
        $this->assertNotNull($compileRequest->getSchema());

        // Assert: The JSON schema was added
        $input = $compileRequest->getSchema();

        // Assert: The schema name is used
        $this->assertEquals($name, $input->getName());
    }
}
