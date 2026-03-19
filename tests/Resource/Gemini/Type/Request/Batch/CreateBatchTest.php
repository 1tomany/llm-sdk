<?php

namespace OneToMany\LlmSdk\Tests\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch\CreateBatch;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function parse_url;

use const PHP_URL_PATH;

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
        $this->expectExceptionMessage('The path "'.parse_url($fileUri, PHP_URL_PATH).'" does not contain a file ID.');

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
