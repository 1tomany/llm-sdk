<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class CompileQueryResponse extends BaseResponse
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

    /**
     *
     * @param string $id
     * @return string
     */
    public function generateHash(string $id): string
    {
        return \hash('sha256', \implode('-', [$id, $this->getModel()->getValue(), \json_encode($this->request)]));
    }

    public function toCreateEmbeddingRequest(): CreateEmbeddingRequest
    {
        return new CreateEmbeddingRequest($this->getModel(), $this->url, $this->request);
    }

    public function toGenerateOutputRequest(): GenerateOutputRequest
    {
        return new GenerateOutputRequest($this->getModel(), $this->url, $this->request);
    }
}
