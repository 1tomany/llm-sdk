<?php

namespace OneToMany\LlmSdk\Response;

readonly class BaseResponse
{
    /**
     * @param non-empty-string $model
     */
    public function __construct(private string $model)
    {
    }

    /**
     * @return non-empty-string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
