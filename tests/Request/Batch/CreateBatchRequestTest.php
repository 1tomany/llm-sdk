<?php

namespace OneToMany\LlmSdk\Tests\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Batch\CreateBatchRequest;
use PHPUnit\Framework\TestCase;

final class CreateBatchRequestTest extends TestCase
{
    public function testConstructorRequiresNonEmptyTrimmedName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name cannot be empty.');

        new CreateBatchRequest(Model::Mock, '  ', null);
    }
}
