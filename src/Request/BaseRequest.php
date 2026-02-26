<?php

namespace OneToMany\LlmSdk\Request;

use function strtolower;
use function trim;

abstract class BaseRequest
{
    /** @var non-empty-lowercase-string */
    protected string $type = 'request';

    /**
     * @param non-empty-lowercase-string $model
     */
    public function __construct(private string $model = 'mock')
    {
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function forModel(string $model): static
    {
        $this->model = strtolower(trim($model)) ?: $this->model;

        return $this;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
