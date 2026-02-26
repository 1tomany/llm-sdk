<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Request\BaseRequest;

class DeleteRequest extends BaseRequest
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(string $model, private string $uri)
    {
        parent::__construct($model);
    }

    /**
     * @see OneToMany\LlmSdk\Request\BaseRequest
     */
    public function getRequestType(): string
    {
        return 'file.delete';
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
