<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function basename;
use function fclose;
use function filesize;
use function fopen;
use function is_file;
use function is_readable;
use function is_resource;
use function mime_content_type;
use function sprintf;
use function strtolower;
use function trim;

class UploadRequest extends BaseRequest
{
    private string $path = '';
    private string $name = '';
    private ?int $size = null;

    /** @var ?non-empty-lowercase-string */
    private ?string $format = null;

    /** @var ?non-empty-lowercase-string */
    private ?string $purpose = null;

    /** @var ?resource */
    private mixed $fileHandle = null;

    public function __destruct()
    {
        $this->closeFile();
    }

    public function atPath(?string $path): static
    {
        $this->path = trim($path ?? '');

        return $this->withName(basename($this->path));
    }

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
     * @return non-negative-int
     *
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
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        if (null === $this->format) {
            if (is_file($this->getPath()) && is_readable($this->getPath())) {
                $this->withFormat(mime_content_type($this->getPath()) ?: null);
            }
        }

        return $this->format ?? 'application/octet-stream';
    }

    public function withPurpose(?string $purpose): static
    {
        $this->purpose = strtolower(trim($purpose ?? '')) ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-lowercase-string
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
