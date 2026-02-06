<?php

namespace OneToMany\AI\Request\Query\Component;

use OneToMany\AI\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;

final readonly class SchemaComponent implements ComponentInterface
{
    /**
     * @param array<string, mixed> $schema
     * @param ?non-empty-string $name
     */
    public function __construct(
        private array $schema,
        private ?string $name,
        private bool $isStrict = true,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name ?? 'json_schema';
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

    /**
     * @see OneToMany\AI\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
