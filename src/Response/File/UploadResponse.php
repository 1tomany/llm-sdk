<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

use function strtolower;

final readonly class UploadResponse extends BaseResponse
{
    /**
     * @param non-empty-string $uri
     * @param ?non-empty-string $name
     * @param ?non-empty-string $purpose
     */
    public function __construct(
        Vendor $vendor,
        private string $uri,
        private ?string $name = null,
        private ?string $purpose = null,
        private ?\DateTimeImmutable $expiresAt = null,
    ) {
        parent::__construct($vendor);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ?non-empty-string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public function getPurpose(): ?string
    {
        return $this->purpose ? strtolower($this->purpose) : null;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
