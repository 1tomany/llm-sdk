<?php

namespace OneToMany\LlmSdk\Response;

abstract readonly class BaseResponse
{
    public function __construct(private string $model)
    {
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
