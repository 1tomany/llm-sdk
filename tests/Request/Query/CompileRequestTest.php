<?php

namespace OneToMany\LlmSdk\Tests\Request\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;
use function uniqid;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
final class CompileRequestTest extends TestCase
{
    public function testUsingBatchKeyNullifiesEmptyTrimmedBatchKeys(): void
    {
        $compileRequest = new CompileRequest();
        $this->assertNull($compileRequest->getBatchKey());

        $compileRequest->usingBatchKey(' ');
        $this->assertNull($compileRequest->getBatchKey());
    }

    public function testWithFileUriRequiresModelToSupportFileInputs(): void
    {
        $model = Model::GptEmbedding3Large;
        $this->assertFalse($model->supportsFiles());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "gpt-embedding-3-large" does not support file inputs.');

        new CompileRequest($model)->withFileUri(uniqid(), 'image/jpeg');
    }

    public function testUsingInstructionsRequiresNonEmbeddingModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertTrue($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock-embedding" does not support system instructions.');

        new CompileRequest($model)->usingInstructions('You are a helpful large language model.');
    }

    public function testUsingDimensionsRequiresEmbeddingModel(): void
    {
        $model = Model::Mock;
        $this->assertFalse($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock" does not support changing the output dimensions.');

        new CompileRequest($model)->usingDimensions(random_int(1, 1024));
    }

    public function testUsingSchemaRequiresNonEmbeddingModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertTrue($model->isEmbedding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "mock-embedding" does not support structured output.');

        new CompileRequest($model)->usingSchema(['title' => 'JsonSchema']);
    }

    public function testUsingSchemaExtractsNameFromTitle(): void
    {
        // Arrange: JSON Schema
        $jsonSchema = ['title' => 'Identify'];

        // Arrange: Compile query request
        $compileRequest = new CompileRequest();
        $this->assertNull($compileRequest->getJsonSchema());

        // Act: Add the JSON schema to the request
        $compileRequest->usingSchema($jsonSchema);
        $this->assertNotNull($compileRequest->getJsonSchema());

        // Assert: The JSON schema was added
        $component = $compileRequest->getJsonSchema();

        // Assert: The title of the JSON schema is used as the name
        $this->assertInstanceOf(SchemaComponent::class, $component);
        $this->assertEquals($jsonSchema['title'], $component->getName());
    }

    public function testUsingSchemaSetsDefaultNameWhenTitleIsMissing(): void
    {
        $compileRequest = new CompileRequest();
        $this->assertNull($compileRequest->getJsonSchema());

        $compileRequest->usingSchema(['required' => ['id']]);
        $this->assertNotNull($compileRequest->getJsonSchema());

        // Assert: The JSON schema was added
        $component = $compileRequest->getJsonSchema();

        $this->assertInstanceOf(SchemaComponent::class, $component);
        $this->assertEquals('JsonSchema', $component->getName());
    }

    public function testUsingSchemaWithName(): void
    {
        // Arrange: Schema name
        $schemaName = 'Identity';

        // Arrange: JSON schema
        $jsonSchema = [
            'title' => 'ID',
            'required' => [
                'name',
                'age',
                'weight',
                'height',
            ],
        ];

        // Assert: Schema name and schema title are not equal
        $this->assertNotEquals($schemaName, $jsonSchema['title']);

        // Arrange: Compile query request
        $compileRequest = new CompileRequest();
        $this->assertNull($compileRequest->getJsonSchema());

        // Act: Add the JSON schema to the request
        $compileRequest->usingSchema($jsonSchema, $schemaName);
        $this->assertNotNull($compileRequest->getJsonSchema());

        // Assert: The JSON schema was added
        $component = $compileRequest->getJsonSchema();

        // Assert: The schema name is used instead of the title
        $this->assertInstanceOf(SchemaComponent::class, $component);
        $this->assertEquals($schemaName, $component->getName());
    }
}
