<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;

use function basename;
use function filesize;
use function fopen;
use function sprintf;
use function strtolower;
use function trim;

class UploadFileRequest
{
    private Vendor $vendor;

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
        private string $path,
    )
    {
        $this->vendor = Vendor::create($vendor);
        $this->withName(null);
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

    public function withName(?string $name): static
    {
        $this->name = trim($name ?? '') ?: basename($this->getPath());

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
            $this->size = @filesize($this->getPath()) ?: throw new RuntimeException(sprintf('Calculating the size of the file "%s" failed.', $this->getName()));
        }

        return $this->size;
    }

    public function withFormat(?string $format): static
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

    public function withPurpose(?string $purpose): static
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
        return @fopen($this->getPath(), 'r') ?: throw new RuntimeException(sprintf('Opening the file "%s" failed.', $this->getPath()));
    }
}
