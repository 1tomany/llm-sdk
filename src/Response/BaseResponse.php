<?php

namespace OneToMany\LlmSdk\Response;

readonly class BaseResponse
{
    public function __construct(private string $model)
    {
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
