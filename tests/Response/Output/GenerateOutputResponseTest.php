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
    public function testToRecordRequiresOutputToBeValidJson(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Converting the output from a JSON string to a record failed.');

        new GenerateOutputResponse(Model::Mock, 'id_123', [], '{"invalid')->toRecord();
    }
}
