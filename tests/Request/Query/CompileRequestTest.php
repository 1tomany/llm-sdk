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
        $compileRequest = new CompileRequest();
        $this->assertCount(0, $compileRequest->getComponents());

        $schema = [
            'title' => 'Identify',
        ];

        $compileRequest->usingSchema($schema);
        $this->assertCount(1, $compileRequest->getComponents());

        $component = $compileRequest->getComponents()[0];
        assert($component instanceof SchemaComponent);

        $this->assertInstanceOf(SchemaComponent::class, $component);
        $this->assertEquals($schema['title'], $component->getName());
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

    public function testHasComponentsIsFalseWhenTheContentsAreEmpty(): void
    {
        $request = new CompileRequest();

        $this->assertFalse($request->hasComponents());
        $this->assertCount(0, $request->getComponents());
    }

    public function testHasComponentsIsTrueWhenTheContentsAreNotEmpty(): void
    {
        $request = new CompileRequest()->withPrompt(...[
            'prompt' => 'When was PHP first released?',
        ]);

        $this->assertTrue($request->hasComponents());
        $this->assertCount(1, $request->getComponents());
    }
}
