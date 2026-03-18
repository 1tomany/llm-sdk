<?php

namespace OneToMany\LlmSdk\Tests\Action\Query;

use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientContainer;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
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
        $this->expectExceptionMessage('The query does not have any input components.');

        new CompileQueryAction(new ClientFactory(new ClientContainer()))->act(new CompileQueryRequest());
    }
}
