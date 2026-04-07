<?php

namespace OneToMany\LlmSdk\Tests\Request\Type\Query\Tool;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Type\Query\Tool\SearchStore;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
#[Group('ToolTests')]
final class SearchStoreTest extends TestCase
{
    public function testCreatingSearchStoreRequiresNonEmptyTrimmedUri(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The search store URI cannot be empty.');

        SearchStore::create(null);
    }
}
