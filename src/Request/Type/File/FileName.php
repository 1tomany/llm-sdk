<?php

namespace OneToMany\LlmSdk\Request\Type\File;

use OneToMany\LlmSdk\Request\Type\Enum\Role;

final readonly class FileName
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        private string $name,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return array{
     *   name: non-empty-string,
     *   role: non-empty-lowercase-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role->getValue(),
        ];
    }
}
