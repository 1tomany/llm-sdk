<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\Trait\UsesModelTrait;

class ProcessQueryRequest
{
    use UsesModelTrait;

    /**
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     */
    public function __construct(
        string|Model $model,
        private readonly string $url,
        private readonly array $request,
    ) {
        $this->usingModel($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
