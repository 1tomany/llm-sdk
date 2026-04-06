<?php

namespace OneToMany\LlmSdk\Tests\Request\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use PHPUnit\Framework\TestCase;

final class CreateSearchStoreRequestTest extends TestCase
{
    public function testConstructorRequiresNonEmptyTrimmedName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name cannot be empty.');

        new CreateSearchStoreRequest(Vendor::Mock, '  ', null);
    }
}
