<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;

class FileRequest
{
    private readonly Vendor $vendor;

    public function __construct(
        string|Vendor|null $vendor = Vendor::Mock,
    ) {
        $this->vendor = Vendor::create($vendor);
    }

    public static function fromModel(string|Model|null $model = Model::Mock): static
    {
        return new static(Model::create($model)->getVendor());
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }
}
