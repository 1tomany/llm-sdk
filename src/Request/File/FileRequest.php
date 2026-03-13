<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;

class FileRequest
{
    private Vendor $vendor;

    public function __construct(
        string|Vendor|null $vendor = Vendor::Mock,
    ) {
        $this->forVendor($vendor);
    }

    public static function fromModel(string|Model|null $model = Model::Mock): self
    {
        return new self(Model::create($model)->getVendor());
    }

    /**
     * @throws InvalidArgumentException when the vendor does not exist
     */
    public function forVendor(string|Vendor|null $vendor): static
    {
        $this->vendor = Vendor::create($vendor);

        return $this;
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }
}
