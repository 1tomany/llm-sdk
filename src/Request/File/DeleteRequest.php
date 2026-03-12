<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

class DeleteRequest extends FileRequest
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        string|Vendor|null $vendor,
        private string $uri,
    ) {
        parent::__construct($vendor);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
