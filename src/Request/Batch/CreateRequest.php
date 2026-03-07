<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;
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
        if (!$name = trim($name ?? '')) {
            throw new InvalidArgumentException('The name cannot be empty.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name ?: throw new RuntimeException('The name is empty.');
    }

    public function withFileUri(?string $fileUri): static
    {
        $this->fileUri = trim($fileUri ?? '') ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFileUri(): ?string
    {
        return $this->fileUri ?: null;
    }

    public function withFileName(?string $fileName): static
    {
        $this->fileName = trim($fileName ?? '') ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFileName(): ?string
    {
        return $this->fileName ?: null;
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
