<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;

use function is_string;
use function trim;

class CompileRequest extends BaseRequest
{
    private ?string $batchKey = null;

    /**
     * @var list<ComponentInterface>
     */
    private array $components = [];

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
     */
    public function withFileUri(?string $fileUri, ?string $format): static
    {
        if (null !== $fileUri && null !== $format) {
            $this->addComponent(new FileUriComponent($fileUri, $format));
        }

        return $this;
    }

    /**
     * @param ?array<string, mixed> $schema
     * @param ?non-empty-string $name
     */
    public function usingSchema(?array $schema, ?string $name = null): static
    {
        if (!$schema) {
            return $this;
        }

        $name = trim($name ?? '');

        if (!$name && isset($schema['title'])) {
            if (is_string($schema['title'])) {
                $name = trim($schema['title']);
            }
        }

        return $this->addComponent(new SchemaComponent($schema, $name ?: null));
    }

    public function withPrompt(?string $prompt, Role $role = Role::User): static
    {
        if ($prompt = trim($prompt ?? '')) {
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
        return [] !== $this->getComponents();
    }
}
