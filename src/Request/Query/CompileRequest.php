<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;

use function array_filter;
use function count;
use function is_string;
use function trim;

class CompileRequest extends BaseRequest
{
    private ?string $batchKey = null;

    /**
     * @var list<ComponentInterface>
     */
    private array $components = [];

    /**
     * @var ?positive-int
     */
    private ?int $dimensions = null;

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
     * @throws InvalidArgumentException when the model is a single-modality model
     */
    public function withFileUri(?string $fileUri, ?string $format): static
    {
        if (null !== $fileUri && null !== $format) {
            if (!$this->getModel()->isMultiModal()) {
                throw new InvalidArgumentException('Files cannot be added to single-modality models.');
            }

            $this->addComponent(new FileUriComponent($fileUri, $format));
        }

        return $this;
    }

    /**
     * @param ?array<string, mixed> $schema
     * @param ?non-empty-string $name
     *
     * @throws InvalidArgumentException when the model is an embedding model
     * @throws InvalidArgumentException when the model is a single-modality model
     */
    public function usingSchema(?array $schema, ?string $name = null): static
    {
        if (!$schema) {
            return $this;
        }

        if ($this->getModel()->isEmbedding()) {
            throw new InvalidArgumentException('Schemas cannot be added to a query using an embedding model.');
        }

        if (!$this->getModel()->isMultiModal()) {
            throw new InvalidArgumentException('Schemas cannot be added to single-modality models.');
        }

        $name = trim($name ?? '');

        if (!$name && isset($schema['title'])) {
            if (is_string($schema['title'])) {
                $name = trim($schema['title']);
            }
        }

        return $this->addComponent(new SchemaComponent($schema, $name ?: null));
    }

    /**
     * @throws InvalidArgumentException when the role is a system role and the model is an embedding model
     */
    public function withPrompt(?string $prompt, Role $role = Role::User): static
    {
        if ($prompt = trim($prompt ?? '')) {
            if ($this->getModel()->isEmbedding() && $role->isSystem()) {
                throw new InvalidArgumentException('System prompts cannot be added to a query using an embedding model.');
            }

            $this->addComponent(new PromptComponent($prompt, $role));
        }

        return $this;
    }

    public function withInstructions(?string $instructions): static
    {
        return $this->withPrompt($instructions, Role::System);
    }

    /**
     * @deprecated since 0.3.3, use withPrompt() instead
     */
    public function withText(?string $text, Role $role = Role::User): static
    {
        return $this->withPrompt($text, $role);
    }

    /**
     * @deprecated since 0.3.3, use withInstructions() instead
     */
    public function withSystemText(?string $text): static
    {
        return $this->withPrompt($text, Role::System);
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

    public function addComponent(ComponentInterface $component): static
    {
        $this->components[] = $component;

        return $this;
    }

    /**
     * @return list<ComponentInterface>
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @phpstan-assert-if-true non-empty-list<ComponentInterface> $this->getComponents()
     */
    public function hasComponents(): bool
    {
        return 0 !== count($this->components);
    }

    /**
     * @phpstan-assert-if-true non-empty-list<ComponentInterface> $this->getComponents()
     */
    public function hasUserComponents(): bool
    {
        $filter = function (ComponentInterface $c): bool {
            return $c->getRole()->isUser();
        };

        return 0 !== count(array_filter($this->components, $filter));
    }
}
