<?php

namespace OneToMany\LlmSdk\Tests\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch\CreateBatch;
use PHPUnit\Framework\TestCase;

final class CreateBatchTest extends TestCase
{
    public function testConstructorRequiresFileUriToContainPathComponent(): void
    {
        $fileUri = 'https://generativelanguage.googleapis.com';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The file URI "'.$fileUri.'" does not contain a path component.');

        new CreateBatch('TestBatch', $fileUri);
    }

    public function testConstructorRequiresFileUriToContainFileId(): void
    {
        $fileUri = 'https://generativelanguage.googleapis.com/';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The path "/" does not contain a file ID.');

        new CreateBatch('TestBatch', $fileUri);
    }
}
