<?php

namespace OneToMany\LlmSdk\Request;

use OneToMany\LlmSdk\Contract\Enum\Model;

use function trim;

class BaseRequest
{
    public function __construct(private Model $model = Model::Mock)
    {
    }

    public function forModel(string|Model|null $model): static
    {
        $this->model = trim($model ?? '') ?: 'mock';

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
