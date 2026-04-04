<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\UsesModelTrait;

use function trim;

class ReadBatchRequest
{
    use UsesModelTrait;

    /**
     * @var non-empty-string
     */
    private string $uri;

    public function __construct(
        string|Model $model,
        ?string $uri,
    ) {
        $this->usingModel($model)->usingUri($uri);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    public function usingUri(?string $uri): static
    {
        if (!$uri = trim((string) $uri)) {
            throw new InvalidArgumentException('The URI cannot be empty.');
        }

        $this->uri = $uri;

        return $this;
    }
}
