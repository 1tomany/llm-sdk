<?php

namespace OneToMany\LlmSdk\Request\Query\Component;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

use function trim;

final readonly class JsonSchemaInput
{
    /**
     * @param array<string, mixed> $schema
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
        return trim($this->name ?? '') ?: 'JsonSchema';
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

    public function getRole(): Role
    {
        return $this->role;
    }
}
