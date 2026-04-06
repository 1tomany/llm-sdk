<?php

namespace OneToMany\LlmSdk\Tests\Request\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use PHPUnit\Framework\TestCase;

final class ImportUploadedFileRequestTest extends TestCase
{
    public function testConstructorRequiresNonEmptyTrimmedSearchStoreUri(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The search store URI cannot be empty.');

        new ImportUploadedFileRequest(Vendor::Mock, '  ', null);
    }
}
