<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;

use function trim;

class DeleteRequest extends FileRequest
{
    /**
     * @var ?non-empty-string
     */
    private ?string $uri = null;

    /**
     * @throws InvalidArgumentException when the trimmed URI is empty
     */
    public function usingUri(?string $uri): static
    {
        if (!$uri = trim($uri ?? '')) {
            throw new InvalidArgumentException('The URI cannot be empty.');
        }

        $this->uri = $uri;

        return $this;
    }

    /**
     * @return non-empty-string
     *
     * @throws RuntimeException when the URI is empty
     */
    public function getUri(): string
    {
        return $this->uri ?? throw new RuntimeException('The URI is empty.');
    }
}
