<?php

namespace OneToMany\LlmSdk\Request;

use function strtolower;
use function trim;

abstract class BaseRequest
{
    public function __construct(private string $model = 'mock')
    {
    }

    public function forModel(string $model): static
    {
        $this->model = strtolower(trim($model));

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
