<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

final readonly class DeleteResponse extends BaseResponse
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        Vendor $vendor,
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
