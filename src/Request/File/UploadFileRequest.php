<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\RuntimeException;

use function basename;
use function filesize;
use function fopen;
use function sprintf;
use function strtolower;
use function trim;

class UploadFileRequest
{
    private readonly Vendor $vendor;

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
     * @var ?non-empty-lowercase-string
     */
    private ?string $purpose = null;

    /**
     * @param non-empty-string $path
     */
    public function __construct(
        string|Vendor $vendor,
        private readonly string $path,
    ) {
        $this->vendor = Vendor::create($vendor);
        $this->usingName(null);
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return non-empty-string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function usingName(?string $name): static
    {
        if (!$name = trim($name ?? '')) {
            $name = basename($this->path);
        }

        $this->name = $name ?: null;

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
            if (false === $size = @filesize($this->getPath())) {
                throw new RuntimeException(sprintf('Calculating the size of the file "%s" failed.', $this->getName()));
            }

            $this->size = $size;
        }

        return $this->size;
    }

    public function usingFormat(?string $format): static
    {
        $this->format = strtolower(trim($format ?? '')) ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function usingPurpose(?string $purpose): static
    {
        $this->purpose = strtolower(trim($purpose ?? '')) ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getPurpose(): ?string
    {
        return $this->purpose ?: null;
    }

    /**
     * @return resource
     *
     * @throws RuntimeException when opening the file fails
     */
    public function openFile(): mixed
    {
        return @fopen($this->getPath(), 'r') ?: throw new RuntimeException(sprintf('Opening the file "%s" failed.', $this->getName()));
    }
}
