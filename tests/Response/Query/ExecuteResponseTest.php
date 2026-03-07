<?php

namespace OneToMany\LlmSdk\Tests\Response\Query;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('QueryTests')]
final class ExecuteResponseTest extends TestCase
{
    public function testGettingResponseRequiresResponseToBeValidJson(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Decoding the response failed.');

        new ExecuteResponse('test-model', 'id_123', '', '{"invalid')->getResponse();
    }

    public function testToRecordRequiresOutputToBeValidJson(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Decoding the output failed.');

        new ExecuteResponse('test-model', 'id_123', '{"invalid', '')->toRecord();
    }
}
