<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function trim;

class DeleteRequest extends FileRequest
{
    /**
     * @var non-empty-string
     */
    private string $uri = 'mock-uri';

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
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
