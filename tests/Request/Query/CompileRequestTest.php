<?php

namespace OneToMany\LlmSdk\Tests\Request\Query;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function assert;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
final class CompileRequestTest extends TestCase
{
    public function testUsingSchemaExtractsNameFromTitle(): void
    {
        // Arrange: JSON Schema
        $jsonSchema = ['title' => 'Identify'];

        // Arrange: Compile query request
        $compileRequest = new CompileRequest();
        $this->assertCount(0, $compileRequest->getComponents());

        // Act: Add the JSON schema to the request
        $compileRequest->usingSchema($jsonSchema);
        $this->assertCount(1, $compileRequest->getComponents());

        // Assert: The JSON schema was added to the request
        $component = $compileRequest->getComponents()[0];
        assert($component instanceof SchemaComponent);

        // Assert: The title of the JSON schema is used as the name
        $this->assertInstanceOf(SchemaComponent::class, $component);
        $this->assertEquals($jsonSchema['title'], $component->getName());
    }

    public function testUsingSchemaSetsDefaultNameWhenTitleIsMissing(): void
    {
        $compileRequest = new CompileRequest();
        $this->assertCount(0, $compileRequest->getComponents());

        $compileRequest->usingSchema(['required' => ['id']]);
        $this->assertCount(1, $compileRequest->getComponents());

        $component = $compileRequest->getComponents()[0];
        assert($component instanceof SchemaComponent);

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
        $this->assertCount(0, $compileRequest->getComponents());

        // Act: Add the JSON schema to the request
        $compileRequest->usingSchema($jsonSchema, $schemaName);
        $this->assertCount(1, $compileRequest->getComponents());

        // Assert: The JSON schema was added to the request
        $component = $compileRequest->getComponents()[0];
        assert($component instanceof SchemaComponent);

        // Assert: The schema name is used instead of the title
        $this->assertInstanceOf(SchemaComponent::class, $component);
        $this->assertEquals($schemaName, $component->getName());
    }

    public function testHasComponentsIsFalseWhenTheContentsAreEmpty(): void
    {
        $compileRequest = new CompileRequest();

        $this->assertFalse($compileRequest->hasComponents());
        $this->assertCount(0, $compileRequest->getComponents());
    }

    public function testHasComponentsIsTrueWhenTheContentsAreNotEmpty(): void
    {
        $compileRequest = new CompileRequest()->withPrompt(...[
            'prompt' => 'When was PHP first released?',
        ]);

        $this->assertTrue($compileRequest->hasComponents());
        $this->assertCount(1, $compileRequest->getComponents());
    }
}
