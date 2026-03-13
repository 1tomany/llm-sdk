<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Exception\RuntimeException;

class DeleteRequest extends FileRequest
{
    /**
     * @var ?non-empty-string
     */
    private ?string $uri = null;

    public function usingUri(?string $uri): static
    {
        $this->uri = \trim($uri ?? '') ?: null;

        return $this;
    }

    /**
     * @return non-empty-string
     *
     * @throws RuntimeException when the URI is empty
     */
    public function getUri(): string
    {
        return $this->uri ?? throw new RuntimeException('The URI cannot be empty.');
    }
}
