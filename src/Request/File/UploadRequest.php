<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function basename;
use function fclose;
use function filesize;
use function fopen;
use function is_resource;
use function sprintf;
use function trim;

class UploadRequest extends BaseRequest
{
    /**
     * @var non-empty-string
     */
    private string $path;
    private string $name;
    private ?int $size = null;
    private ?string $format = null;
    private ?string $purpose = null;

    /**
     * @var ?resource
     */
    private mixed $fileHandle = null;

    public function __construct(
        string $model,
        string $path,
    ) {
        parent::__construct($model);

        $this->atPath($path);
    }

    public function __destruct()
    {
        $this->closeFile();
    }

    public function atPath(?string $path): static
    {
        if (!$path = trim($path ?? '')) {
            throw new InvalidArgumentException('The path cannot be empty.');
        }

        $this->path = $path;
        // \PHPStan\dumpType($this->path);

        return $this->withName(basename($path));
    }

    /**
     * @return non-empty-string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function withName(?string $name): static
    {
        $this->name = trim($name ?? '');

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws RuntimeException when the size of the file could not be calculated
     */
    public function getSize(): int
    {
        if (null === $this->size) {
            $this->size = @filesize((string) $this->path) ?: throw new RuntimeException(sprintf('Calculating the size of the file "%s" failed.', $this->getName()));
        }

        return $this->size;
    }

    public function withFormat(?string $format): static
    {
        $this->format = trim($format ?? '') ?: null;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function withPurpose(?string $purpose): static
    {
        $this->purpose = trim($purpose ?? '') ?: null;

        return $this;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    /**
     * @return resource
     *
     * @throws RuntimeException when opening the file fails
     */
    public function openFile(): mixed
    {
        if (null === $this->fileHandle) {
            $this->fileHandle = @fopen((string) $this->path, 'r') ?: throw new RuntimeException(sprintf('Opening the file "%s" failed.', $this->getName()));
        }

        return $this->fileHandle;
    }

    public function closeFile(): void
    {
        if (is_resource($this->fileHandle)) {
            @fclose($this->fileHandle);
        }

        $this->fileHandle = null;
    }
}
