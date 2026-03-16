<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

final readonly class TextInput
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(
        private string $text,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return array{
     *   text: non-empty-string,
     *   role: non-empty-lowercase-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'role' => $this->role->getValue(),
        ];
    }
}
