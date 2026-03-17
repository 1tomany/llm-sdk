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
    public function testGeneratingHash(): void
    {
        $response = new CompileQueryResponse(Model::Mock, 'https://mock-llm.service/outputs', ['id' => 1, 'name' => 'Vic']);

        $this->assertEquals('e6c5a928299d241125a89cf7c1fdab499671146a00dec1606c32e5b09f77bb9c', $response->generateHash('OTM1'));
    }
}
