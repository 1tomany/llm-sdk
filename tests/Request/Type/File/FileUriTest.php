<?php

namespace OneToMany\LlmSdk\Tests\Request\Type\File;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Type\File\FileUri;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function uniqid;

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

    #[DataProvider('providerFormatAndIsImage')]
    public function testIsImage(?string $format, bool $isImage): void
    {
        $this->assertSame($isImage, new FileUri(uniqid(), $format)->isImage());
    }

    /**
     * @return non-empty-list<non-empty-list<bool|string|null>>
     */
    public static function providerFormatAndIsImage(): array
    {
        $provider = [
            [null, false],
            ['text/plain', false],
            ['application/pdf', false],
            ['image/gif', true],
            ['image/png', true],
            ['image/webp', true],
        ];

        return $provider;
    }
}
