<?php

namespace OneToMany\LlmSdk\Request\File\Search;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Request\File\BaseRequest;

final class CreateStoreRequest extends BaseRequest
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string|Vendor $vendor,
        private readonly string $name,
    ) {
        parent::__construct($vendor);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
