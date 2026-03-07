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
use function strtolower;
use function trim;

class UploadRequest extends BaseRequest
{
    /**
     * @var ?non-empty-string
     */
    private ?string $path = null;

    /**
     * @var ?non-empty-string
     */
    private ?string $name = null;

    /**
     * @var ?non-negative-int
     */
    private ?int $size = null;

    /**
     * @var ?non-empty-lowercase-string
     */
    private ?string $format = null;

    /**
     * @var ?non-empty-string
     */
    private ?string $purpose = null;

    /**
     * @var ?resource
     */
    private mixed $fileHandle = null;

    public function __destruct()
    {
        $this->closeFile();
    }

    /**
     * @throws InvalidArgumentException when the trimmed path is empty
     */
    public function atPath(?string $path): static
    {
        if (!$path = trim($path ?? '')) {
            throw new InvalidArgumentException('The path cannot be empty.');
        }

        $this->path = $path;

        return $this->withName(basename($path));
    }

    /**
     * @return non-empty-string
     *
     * @throws RuntimeException when the path is empty
     */
    public function getPath(): string
    {
        return $this->path ?: throw new RuntimeException('The path cannot be empty.');
    }

    public function withName(?string $name): static
    {
        $this->name = trim($name ?? '') ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getName(): ?string
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
        $this->format = strtolower(trim($format ?? '')) ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function withPurpose(?string $purpose): static
    {
        $this->purpose = trim($purpose ?? '') ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
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
