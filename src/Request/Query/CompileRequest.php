<?php

namespace OneToMany\AI\Request\Query;

use OneToMany\AI\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;
use OneToMany\AI\Request\BaseRequest;
use OneToMany\AI\Request\Query\Component\FileUriComponent;
use OneToMany\AI\Request\Query\Component\SchemaComponent;
use OneToMany\AI\Request\Query\Component\TextComponent;

class CompileRequest extends BaseRequest
{
    /**
     * @var list<ComponentInterface>
     */
    private array $components = [];

    /**
     * @param non-empty-string $fileUri
     * @param non-empty-lowercase-string $format
     */
    public function withFileUri(string $fileUri, string $format): static
    {
        return $this->addComponent(new FileUriComponent($fileUri, $format));
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
     * @param non-empty-string $text
     */
    public function withText(string $text, Role $role = Role::User): static
    {
        return $this->addComponent(new TextComponent($text, $role));
    }

    /**
     * @param non-empty-string $text
     */
    public function withSystemText(string $text): static
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
