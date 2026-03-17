<?php

namespace OneToMany\LlmSdk\Tests\Request\File;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

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
        // Arrange: Create a temporary file
        $this->assertFileExists($path = tempnam(sys_get_temp_dir(), '__onetomany_llmsdk__'));

        // Arrange: Create the request to upload the file
        $uploadRequest = new UploadFileRequest('mock', $path);

        // Assert: File must exist to calculate the size
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Calculating the size of the file "'.$uploadRequest->getName().'" failed.');

        // Act: Delete the file
        $this->assertTrue(unlink($path));
        $this->assertFileDoesNotExist($path);

        // Act: Get the size
        $uploadRequest->getSize();
    }
}
