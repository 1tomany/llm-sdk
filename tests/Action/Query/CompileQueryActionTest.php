<?php

namespace OneToMany\AI\Tests\Action\Query;

use OneToMany\AI\Action\Query\CompileQueryAction;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Factory\ClientFactory;
use OneToMany\AI\Request\Query\CompileRequest;
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
