<?php

namespace OneToMany\LlmSdk\Request\Query\Type;

use function is_object;
use function is_string;
use function trim;

final readonly class SchemaInput
{
    /**
     * @param non-empty-string $name
     * @param array<string, mixed> $schema
     * @param non-empty-lowercase-string $format
     */
    public function __construct(
        private string $name,
        private array $schema,
        private string $format = 'application/json',
        private bool $strict = true,
    ) {
    }

    /**
     * @param array<string, mixed>|SchemaInput $schema
     */
    public static function create(?string $name, array|self $schema): self
    {
        if (is_object($schema)) {
            return $schema;
        }

        if (is_string($schema['title'] ?? null)) {
            $name = trim($name ?? $schema['title']);
        }

        return new self($name ?: 'JsonSchema', $schema);
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
        return $this->format;
    }

    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * @return array{
     *   name: non-empty-string,
     *   schema: array<string, mixed>,
     *   format: non-empty-lowercase-string,
     *   strict: bool,
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'schema' => $this->schema,
            'format' => $this->format,
            'strict' => $this->strict,
        ];
    }
}
