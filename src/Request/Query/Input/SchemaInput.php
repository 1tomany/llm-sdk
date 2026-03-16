<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use function is_object;
use function is_string;
use function trim;

final readonly class SchemaInput
{
    /**
     * @param array<string, mixed> $schema
     * @param non-empty-string $name
     */
    public function __construct(
        private array $schema,
        private string $name,
        private bool $isStrict = true,
    ) {
    }

    /**
     * @param array<string, mixed>|SchemaInput $schema
     */
    public static function create(array|self $schema, ?string $name): self
    {
        if (is_object($schema)) {
            return $schema;
        }

        if (is_string($schema['title'] ?? null)) {
            $name = trim($name ?? $schema['title']);
        }

        return new self($schema, $name ?: 'JsonSchema');
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        return 'application/json';
    }

    public function isStrict(): bool
    {
        return $this->isStrict;
    }
}
