<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function trim;

class ExecuteRequest extends BaseRequest
{
    /**
     * @var ?non-empty-string
     */
    private ?string $url = null;

    /**
     * @var array<string, mixed>
     */
    private array $request = [];

    public function withUrl(?string $url): static
    {
        if (!$url = trim($url ?? '')) {
            throw new InvalidArgumentException('The URL cannot be empty.');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url ?? throw new InvalidArgumentException('The URL is empty.');
    }

    /**
     * @param array<string, mixed> $request
     */
    public function withRequest(array $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
