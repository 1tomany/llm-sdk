<?php

namespace OneToMany\LlmSdk\Request;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;

class BaseRequest
{
    private readonly Model $model;

    /**
     * @throws InvalidArgumentException when the model is not valid
     */
    public function __construct(
        string|Model|null $model = Model::Mock,
    ) {
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
