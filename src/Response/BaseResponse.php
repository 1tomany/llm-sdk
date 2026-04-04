<?php

namespace OneToMany\LlmSdk\Response;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;

readonly class BaseResponse
{
    private Model $model;

    public function __construct(
        string|Model $model,
    )
    {
        $this->model = Model::create($model);
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getVendor(): Vendor
    {
        return $this->model->getVendor();
    }
}
