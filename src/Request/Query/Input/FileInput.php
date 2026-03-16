<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

use function str_starts_with;

final readonly class FileInput
{
    /**
     * @param non-empty-string $uri
     * @param non-empty-lowercase-string $format
     */
    public function __construct(
        private string $uri,
        private string $format,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function isImage(): bool
    {
        return str_starts_with($this->format, 'image/');
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
