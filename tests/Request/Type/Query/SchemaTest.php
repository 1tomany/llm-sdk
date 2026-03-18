<?php

namespace OneToMany\LlmSdk\Tests\Request\Type\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Type\Query\Schema;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
final class SchemaTest extends TestCase
{
    public function testConstructorRequiresSchemaToHaveTitleProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The schema requires the "title" property.');

        new Schema(['properties' => ['id' => 'string']]);
    }

    public function testConstructorRequiresSchemaToHaveNonEmptyTrimmedTitleProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "title" property cannot be empty.');

        new Schema(['title' => '     ']);
    }
}
