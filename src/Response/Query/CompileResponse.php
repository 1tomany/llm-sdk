<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class CompileResponse extends BaseResponse
{
    /**
     * @param array<string, mixed> $request
     */
    public function __construct(
        Model $model,
        private array $request,
    ) {
        parent::__construct($model);
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    public function toGenerateOutputRequest(): GenerateOutputRequest
    {
        return new GenerateOutputRequest($this->getModel(), $this->request);
    }

    // public function toExecuteRequest(): ExecuteRequest
    // {
    //     return new ExecuteRequest($this->getModel())->withUrl($this->getUrl())->withRequest($this->getRequest());
    // }
}
