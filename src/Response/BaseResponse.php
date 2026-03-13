<?php

namespace OneToMany\LlmSdk\Response;

use OneToMany\LlmSdk\Contract\Enum\Model;

readonly class BaseResponse
{
    public function __construct(private Model $model)
    {
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
