<?php

namespace OneToMany\LlmSdk\Tests\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('QueryTests')]
final class CompileQueryResponseTest extends TestCase
{
    public function testInvokingCompileQueryResponseReturnsRequest(): void
    {
        $response = new CompileQueryResponse(Model::Mock, 'https://mock-llm.service/outputs', [
            'prompt' => 'Give a brief history of PHP.',
        ]);

        $this->assertSame($response->getRequest(), $response());
    }

    public function testGeneratingHash(): void
    {
        $response = new CompileQueryResponse(Model::Mock, 'https://mock-llm.service/outputs', [
            'prompt' => 'Give a brief history of PHP.',
        ]);

        $this->assertEquals('df435ce61a9490ad07a049bf3e482d8f8084fbd3d5df5da0e97bc34baca5cd2d', $response->generateHash('OTM1'));
    }
}
