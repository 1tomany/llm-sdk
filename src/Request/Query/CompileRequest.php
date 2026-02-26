<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\Component\TextComponent;

use function trim;

class CompileRequest extends BaseRequest
{
    /**
     * @var ?non-empty-string
     */
    private ?string $batchKey = null;

    /**
     * @var list<ComponentInterface>
     */
    private array $components = [];

    /**
     * @see OneToMany\LlmSdk\Request\BaseRequest
     */
    public function getRequestType(): string
    {
        return 'request.compile';
    }

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
        return $this->batchKey;
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
     * @param array<string, mixed> $schema
     * @param non-empty-string $name
     */
    public function usingSchema(array $schema, string $name = 'json_schema'): static
    {
        return $this->addComponent(new SchemaComponent($schema, $name));
    }

    /**
     * @param ?non-empty-string $text
     */
    public function withText(?string $text, Role $role = Role::User): static
    {
        if (null !== $text) {
            $this->addComponent(new TextComponent($text, $role));
        }

        return $this;
    }

    /**
     * @param ?non-empty-string $text
     */
    public function withSystemText(?string $text): static
    {
        return $this->withText($text, Role::System);
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
