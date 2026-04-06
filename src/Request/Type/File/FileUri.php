<?php

namespace OneToMany\LlmSdk\Request\Type\File;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Type\Enum\Role;

use function in_array;
use function strtolower;
use function trim;

final readonly class FileUri
{
    /**
     * @param non-empty-string $uri
     * @param ?non-empty-lowercase-string $format
     */
    public function __construct(
        private string $uri,
        private ?string $format = null,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @throws InvalidArgumentException when the trimmed URI is empty
     */
    public static function create(
        string|self|null $uri,
        ?string $format = null,
        Role $role = Role::User,
    ): self {
        if (!$uri instanceof self) {
            if (!$uri = trim((string) $uri)) {
                throw new InvalidArgumentException('The URI cannot be empty.');
            }

            $uri = new FileUri($uri, $format ? strtolower($format) : null, $role);
        }

        return $uri;
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public function getFormat(): ?string
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
     *   format: ?non-empty-lowercase-string,
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
