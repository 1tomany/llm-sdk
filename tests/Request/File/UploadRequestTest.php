<?php

namespace OneToMany\AI\Tests\Request\File;

use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Request\File\UploadRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_key_exists;
use function assert;
use function fclose;
use function stream_get_meta_data;
use function tmpfile;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('FileTests')]
final class UploadRequestTest extends TestCase
{
    public function testGettingSizeRequiresFileToExist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Calculating the size of the file failed.');

        $handle = tmpfile();
        $metadata = stream_get_meta_data($handle);

        assert(array_key_exists('uri', $metadata));
        $this->assertFileExists($metadata['uri']);

        fclose($handle);
        new UploadRequest()->atPath($metadata['uri'])->getSize();
    }
}
