<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final class DeleteFileRequest extends BaseRequest
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        string|Vendor $vendor,
        private readonly string $uri,
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
