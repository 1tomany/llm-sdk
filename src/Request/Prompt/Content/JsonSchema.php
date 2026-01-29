<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;
use OneToMany\AI\Contract\Request\Prompt\Content\Enum\Role;

use function is_string;
use function strtolower;
use function trim;

final readonly class JsonSchema implements ContentInterface
{
    /**
     * @param non-empty-lowercase-string $name
     * @param array<string, mixed> $schema
     * @param non-empty-lowercase-string $format
     */
    public function __construct(
        private string $name,
        private array $schema,
        private string $format = 'application/json',
    ) {
    }

    /**
     * @param array<string, mixed> $schema
     */
    public static function create(?string $name, array $schema): self
    {
        // Attempt to resolve the name
        $name = trim($name ?? '') ?: null;

        if (isset($schema['title'])) {
            $name ??= $schema['title'];
        }

        if (!is_string($name) || !$name) {
            $name = 'json_schema';
        }

        return new self(strtolower($name), $schema);
    }

    /**
     * @return non-empty-lowercase-string
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

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface
     */
    public function getRole(): Role
    {
        return Role::User;
    }
}
