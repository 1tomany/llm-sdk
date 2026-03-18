<?php

namespace OneToMany\LlmSdk\Tests\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function assert;
use function strlen;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('FileTests')]
final class UploadFileRequestTest extends TestCase
{
    /**
     * @var ?non-empty-string
     */
    private ?string $path = null;

    protected function tearDown(): void
    {
        if (null !== $this->path) {
            @unlink($this->path);
        }
    }

    public function testGettingSizeRequiresFileToExist(): void
    {
        // Arrange: Create a file
        $path = $this->createTemporaryFile();

        // Arrange: Create the request to upload the file
        $request = new UploadFileRequest(Vendor::Mock, $path);

        // Assert: File must exist to calculate the size
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Calculating the size of the file "'.$request->getName().'" failed.');

        // Act: Delete the file
        $this->assertTrue(unlink($path));
        $this->assertFileDoesNotExist($path);

        // Act: Get the size
        $request->getSize();
    }

    public function testOpeningFileRequiresFileToExist(): void
    {
        // Arrange: Create a file
        $path = $this->createTemporaryFile();

        // Arrange: Create the request to upload the file
        $request = new UploadFileRequest(Vendor::Mock, $path);

        // Assert: File must exist to open it
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Opening the file "'.$request->getName().'" failed.');

        // Act: Delete the file
        $this->assertTrue(unlink($path));
        $this->assertFileDoesNotExist($path);

        // Act: Open the file
        $request->openFile();
    }

    /**
     * @return non-empty-string
     */
    private function createTemporaryFile(): string
    {
        if (null === $this->path) {
            $this->path = tempnam(sys_get_temp_dir(), '__onetomany_llmsdk__');
        }

        assert(strlen($this->path) > 0);
        $this->assertFileExists($this->path);

        return $this->path;
    }
}
