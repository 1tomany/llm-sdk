<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class CompileResponse extends BaseResponse
{
    /**
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     */
    public function __construct(
        Model $model,
        private string $url,
        private array $request,
    ) {
        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    public function toExecuteRequest(): ExecuteRequest
    {
        return new ExecuteRequest($this->getModel())->withUrl($this->getUrl())->withRequest($this->getRequest());
    }
}
