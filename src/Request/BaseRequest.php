<?php

namespace OneToMany\LlmSdk\Request;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;

class BaseRequest
{
    private Model $model = Model::Mock;

    public function __construct(
        string|Model|null $model = Model::Mock,
    ) {
        $this->forModel($model);
    }

    public function forModel(string|Model|null $model): static
    {
        $this->model = Model::create($model);

        return $this;
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
