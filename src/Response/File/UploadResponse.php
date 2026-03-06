<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Response\BaseResponse;

use function strtolower;

final readonly class UploadResponse extends BaseResponse
{
    public function __construct(
        string $model,
        private string $uri,
        private ?string $name = null,
        private ?string $purpose = null,
        private ?\DateTimeImmutable $expiresAt = null,
    ) {
        parent::__construct($model);
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose ? strtolower($this->purpose) : null;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
