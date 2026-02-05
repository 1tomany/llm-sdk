<?php

namespace OneToMany\AI\Request\Query;

use OneToMany\AI\Request\BaseRequest;

use function trim;

class ExecuteRequest extends BaseRequest
{
    /**
     * @var non-empty-string
     */
    private string $url = 'mock';

    /**
     * @var array<string, mixed>
     */
    private array $request = [];

    public function withUrl(?string $url): static
    {
        $this->url = trim($url ?? '') ?: $this->url;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
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
