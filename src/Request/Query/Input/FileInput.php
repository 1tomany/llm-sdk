<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

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
     * }
     */
    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'format' => $this->format,
        ];
    }
}
