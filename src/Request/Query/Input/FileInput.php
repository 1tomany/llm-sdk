<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

use function in_array;

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

    public function getRole(): Role
    {
        return $this->role;
    }

    public function isImage(): bool
    {
        return in_array($this->format, [
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/webp',
        ]);
    }

    /**
     * @return array{
     *   uri: non-empty-string,
     *   format: non-empty-lowercase-string,
     *   role: non-empty-lowercase-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'format' => $this->format,
            'role' => $this->role->getValue(),
        ];
    }
}
