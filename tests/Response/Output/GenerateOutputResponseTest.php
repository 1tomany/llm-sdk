<?php

namespace OneToMany\LlmSdk\Tests\Response\Output;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('OutputTests')]
final class GenerateOutputResponseTest extends TestCase
{
    public function testInvokingGenerateOutputResponseReturnsResponse(): void
    {
        $response = new GenerateOutputResponse(Model::Mock, 'query_abc123', ['output' => '{"name":"Vic"}']);

        $this->assertSame($response->getResponse(), $response());
    }

    public function testToRecordRequiresOutputToBeValidJson(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Decoding the output failed.');

        new GenerateOutputResponse(Model::Mock, 'query_abc123', [], '{"invalid')->toRecord();
    }
}
