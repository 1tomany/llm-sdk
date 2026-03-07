<?php

namespace OneToMany\LlmSdk\Tests\Action\Query;

use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ActionTests')]
#[Group('QueryTests')]
final class CompileQueryActionTest extends TestCase
{
    public function testCompilingQueryRequiresRequestToHaveAtLeastOneComponent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Compiling the query failed because no components have been added to it.');

        new CompileQueryAction(new ClientFactory([]))->act(new CompileRequest());
    }
}
