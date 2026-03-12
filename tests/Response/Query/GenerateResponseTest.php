<?php

namespace OneToMany\LlmSdk\Tests\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Response\Query\ContentResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('QueryTests')]
final class GenerateResponseTest extends TestCase
{
    public function testToRecordRequiresOutputToBeValidJson(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Decoding the output failed.');

        new ContentResponse(Model::Mock, 'id_123', '{"invalid')->toRecord();
    }
}
