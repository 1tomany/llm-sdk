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
use function max;
use function min;
use function sprintf;
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
     * @var ?int<1, 4096>
     */
    private ?int $dimensions = null;
    private ?SchemaComponent $schema = null;

    public function usingBatchKey(?string $batchKey): static
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
     * @throws InvalidArgumentException when the model does not support file inputs
     */
    public function withFileUri(?string $fileUri, ?string $format): static
    {
        if (null !== $fileUri && null !== $format) {
            if (!$this->getModel()->supportsFiles()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support file inputs.', $this->getModel()->getValue()));
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
     * @throws InvalidArgumentException when the model does not support system instructions
     */
    public function withPrompt(?string $prompt, Role $role = Role::User): static
    {
        if ('' !== $prompt = trim($prompt ?? '')) {
            $component = new PromptComponent($prompt, $role);

            if ($this->getModel()->isEmbedding()) {
                if ($component->getRole()->isSystem()) {
                    throw new InvalidArgumentException(sprintf('The model "%s" does not support system instructions.', $this->getModel()->getValue()));
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

    public function withUserPrompt(?string $prompt): static
    {
        return $this->withPrompt($prompt, Role::User);
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
     * @throws InvalidArgumentException when the model does not support changing the output dimensions
     */
    public function usingDimensions(?int $dimensions): static
    {
        if (null === $dimensions) {
            $this->dimensions = null;
        } else {
            if (!$this->getModel()->isEmbedding()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support changing the output dimensions.', $this->getModel()->getValue()));
            }

            $this->dimensions = min(max(1, $dimensions), 4096);
        }

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
     * @throws InvalidArgumentException when the model does not support structured output
     */
    public function usingSchema(?array $schema, ?string $name = null): static
    {
        if (!$schema) {
            return $this;
        }

        if ($this->getModel()->isEmbedding()) {
            throw new InvalidArgumentException(sprintf('The model "%s" does not support structured output.', $this->getModel()->getValue()));
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
