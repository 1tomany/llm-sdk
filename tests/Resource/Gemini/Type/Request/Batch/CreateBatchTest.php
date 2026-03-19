<?php

namespace OneToMany\LlmSdk\Tests\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch\CreateBatch;
use PHPUnit\Framework\TestCase;

final class CreateBatchTest extends TestCase
{
    public function testConstructorRequiresNonEmptyTrimmedFileUri(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The file URI cannot be empty.');

        new CreateBatch('TestBatch', '  ');
    }
}
