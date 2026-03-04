<?php

namespace OneToMany\LlmSdk\Request\Query\Component;

use OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

final readonly class PromptComponent implements ComponentInterface
{
    /**
     * @param non-empty-string $prompt
     */
    public function __construct(
        private string $prompt,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getPrompt(): string
    {
        return $this->prompt;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
