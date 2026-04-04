<?php

namespace OneToMany\LlmSdk\Request\Trait;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;

trait UsesModelTrait
{
    private Model $model;

    public function getModel(): Model
    {
        return $this->model;
    }

    public function usingModel(string|Model $model): static
    {
        $this->model = Model::create($model);

        return $this;
    }

    public function getVendor(): Vendor
    {
        return $this->model->getVendor();
    }
}
