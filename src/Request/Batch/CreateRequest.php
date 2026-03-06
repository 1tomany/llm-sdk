<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Request\BaseRequest;

use function trim;

class CreateRequest extends BaseRequest
{
    private ?string $name = null;
    private ?string $fileUri = null;
    private ?string $fileName = null;

    /**
     * @var array<string, ?scalar>
     */
    private array $options = [];

    public function withName(?string $name): static
    {
        $this->name = trim($name ?? '') ?: null;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function withFileUri(?string $fileUri): static
    {
        $this->fileUri = trim($fileUri ?? '') ?: null;

        return $this;
    }

    public function getFileUri(): ?string
    {
        return $this->fileUri;
    }

    public function withFileName(?string $fileName): static
    {
        $this->fileName = trim($fileName ?? '') ?: null;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param array<string, ?scalar> $options
     */
    public function withOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array<string, ?scalar>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
