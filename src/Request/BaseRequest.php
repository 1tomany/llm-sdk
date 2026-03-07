<?php

namespace OneToMany\LlmSdk\Request;

use function trim;

abstract class BaseRequest
{
    /**
     * @param non-empty-string $model
     */
    public function __construct(private string $model = 'mock')
    {
    }

    public function forModel(string $model): static
    {
        $this->model = trim($model) ?: 'mock';

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
