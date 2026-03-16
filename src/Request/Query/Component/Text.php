<?php

namespace OneToMany\LlmSdk\Request\Query\Component;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

final readonly class Text
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

    /**
     * @see OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
