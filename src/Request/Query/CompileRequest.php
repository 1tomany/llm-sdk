<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;

use function count;
use function is_string;
use function trim;

class CompileRequest extends BaseRequest
{
    /**
     * @var ?non-empty-string
     */
    private ?string $batchKey = null;

    /**
     * @var list<FileUriComponent>
     */
    private array $files = [];

    /**
     * @var list<PromptComponent>
     */
    private array $prompts = [];
    private ?PromptComponent $instructions = null;

    /**
     * @var ?positive-int
     */
    private ?int $dimensions = null;
    private ?SchemaComponent $schema = null;

    public function withBatchKey(?string $batchKey): static
    {
        $this->batchKey = trim($batchKey ?? '') ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getBatchKey(): ?string
    {
        return $this->batchKey ?: null;
    }

    /**
     * @param ?non-empty-string $fileUri
     * @param ?non-empty-lowercase-string $format
     *
     * @throws InvalidArgumentException when the model is single-modality
     */
    public function withFileUri(?string $fileUri, ?string $format): static
    {
        if (null !== $fileUri && null !== $format) {
            if (!$this->getModel()->isMultiModal()) {
                throw new InvalidArgumentException('Files cannot be used with single-modality models.');
            }

            $this->files[] = new FileUriComponent($fileUri, $format);
        }

        return $this;
    }

    /**
     * @return list<FileUriComponent>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @throws InvalidArgumentException when a system prompt is used with an embedding model
     */
    public function withPrompt(?string $prompt, Role $role = Role::User): static
    {
        if ('' !== $prompt = trim($prompt ?? '')) {
            $component = new PromptComponent($prompt, $role);

            if ($this->getModel()->isEmbedding()) {
                if ($component->getRole()->isSystem()) {
                    throw new InvalidArgumentException('System prompts cannot be used with embedding models.');
                }

                $this->prompts = [$component];
            } else {
                if ($component->getRole()->isSystem()) {
                    $this->instructions = $component;
                } else {
                    $this->prompts[] = $component;
                }
            }
        }

        return $this;
    }

    /**
     * @return list<PromptComponent>
     */
    public function getPrompts(): array
    {
        return $this->prompts;
    }

    public function usingInstructions(?string $instructions): static
    {
        return $this->withPrompt($instructions, Role::System);
    }

    public function getInstructions(): ?PromptComponent
    {
        return $this->instructions;
    }

    /**
     * @throws InvalidArgumentException when the model is not an embedding model
     * @throws InvalidArgumentException when the dimensions are not a positive integer
     */
    public function withDimensions(int $dimensions): static
    {
        if (!$this->getModel()->isEmbedding()) {
            throw new InvalidArgumentException('Output dimensions can only be added to a query using an embedding model.');
        }

        if ($dimensions < 1) {
            throw new InvalidArgumentException('Output dimensions must be a positive integer.');
        }

        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return ?positive-int
     */
    public function getDimensions(): ?int
    {
        return $this->dimensions;
    }

    /**
     * @param ?array<string, mixed> $schema
     * @param ?non-empty-string $name
     *
     * @throws InvalidArgumentException when the model is an embedding model
     */
    public function usingSchema(?array $schema, ?string $name = null): static
    {
        if (!$schema) {
            return $this;
        }

        if ($this->getModel()->isEmbedding()) {
            throw new InvalidArgumentException('Schemas cannot be used with embedding models.');
        }

        $name = trim($name ?? '');

        if (!$name && isset($schema['title'])) {
            if (is_string($schema['title'])) {
                $name = trim($schema['title']);
            }
        }

        $this->schema = new SchemaComponent($schema, $name ?: null);

        return $this;
    }

    public function getSchema(): ?SchemaComponent
    {
        return $this->schema;
    }

    public function hasComponents(): bool
    {
        return 0 !== (count($this->files) + count($this->prompts));
    }
}
