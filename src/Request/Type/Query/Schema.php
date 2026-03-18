<?php

namespace OneToMany\LlmSdk\Request\Type\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function is_object;
use function is_string;
use function trim;

final readonly class Schema
{
    private string $name;

    /**
     * @param array<string, mixed> $schema
     * @param non-empty-lowercase-string $format
     *
     * @throws InvalidArgumentException when the schema is missing the "title" property
     * @throws InvalidArgumentException when the trimmed "title" property is empty
     */
    public function __construct(
        private array $schema,
        private string $format = 'application/json',
        private bool $strict = true,
    ) {
        if (!is_string($schema['title'] ?? null)) {
            throw new InvalidArgumentException('The schema requires the "title" property.');
        }

        if (!$name = trim($schema['title'])) {
            throw new InvalidArgumentException('The "title" property cannot be empty.');
        }

        $this->name = $name;
    }

    /**
     * @param array<string, mixed>|Schema $schema
     */
    public static function create(array|self $schema): self
    {
        if (is_object($schema)) {
            return $schema;
        }

        return new self($schema);
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
