<?php

namespace OneToMany\LlmSdk\Tests\Request\File;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_key_exists;
use function assert;
use function stream_get_meta_data;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('FileTests')]
final class UploadRequestTest extends TestCase
{
    public function testGettingSizeRequiresFileToExist(): void
    {
        $path = tempnam(sys_get_temp_dir(), '__onetomany_llmsdk__');

        // $metadata = stream_get_meta_data($handle);
        // assert(array_key_exists('uri', $metadata));

        $this->assertFileExists($path);
        $uploadRequest = new UploadRequest()->atPath($path);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Calculating the size of the file "'.$uploadRequest->getName().'" failed.');

        unlink($path);
        $uploadRequest->getSize();
    }
}
