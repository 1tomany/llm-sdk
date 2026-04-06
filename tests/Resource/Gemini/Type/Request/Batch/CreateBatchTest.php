<?php

namespace OneToMany\LlmSdk\Tests\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch\CreateBatch;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CreateBatchTest extends TestCase
{
    public function testConstructorRequiresFileUriToContainFileId(): void
    {
        $fileUri = 'https://googleapis.com/';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A file name could not be extracted from the file URI "'.$fileUri.'".');

        new CreateBatch('TestBatch', $fileUri);
    }

    /**
     * @param non-empty-string $fileUri
     * @param non-empty-string $fileName
     */
    #[DataProvider('providerFileUriAndFileName')]
    public function testConstructorGeneratesFileName(string $fileUri, string $fileName): void
    {
        $this->assertEquals($fileName, new CreateBatch('TestBatch', $fileUri)->fileName);
    }

    /**
     * @return non-empty-list<non-empty-list<non-empty-string>>
     */
    public static function providerFileUriAndFileName(): array
    {
        $provider = [
            ['vh3dbpv1pq02', 'files/vh3dbpv1pq02'],
            ['files/vh3dbpv1pq02', 'files/vh3dbpv1pq02'],
            ['/files/vh3dbpv1pq02', 'files/vh3dbpv1pq02'],
            ['v1beta/files/vh3dbpv1pq02', 'files/vh3dbpv1pq02'],
            ['/v1beta/files/vh3dbpv1pq02', 'files/vh3dbpv1pq02'],
            ['https://googleapis.com/v1beta/files/vh3dbpv1pq02', 'files/vh3dbpv1pq02'],
        ];

        return $provider;
    }
}
