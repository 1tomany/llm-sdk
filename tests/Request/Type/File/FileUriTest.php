<?php

namespace OneToMany\LlmSdk\Tests\Request\Type\File;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Type\File\FileUri;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('FileTests')]
final class FileUriTest extends TestCase
{
    public function testCreatingFileUriRequiresNonEmptyTrimmedUri(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The URI cannot be empty.');

        FileUri::create(null);
    }
}
