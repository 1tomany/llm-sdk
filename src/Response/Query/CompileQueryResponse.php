<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\BaseResponse;

use function hash;
use function implode;
use function json_encode;

final readonly class CompileQueryResponse extends BaseResponse
{
    /**
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     */
    public function __construct(
        string|Model $model,
        private string $url,
        private array $request,
    ) {
        parent::__construct($model);
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return $this->getRequest();
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

    public function getHash(string $id): string
    {
        return $this->generateHash($id);
    }

    /**
     * @param non-empty-string $id
     *
     * @return non-empty-lowercase-string
     */
    public function generateHash(string $id): string
    {
        return hash('sha256', implode('-', [$id, $this->getModel()->getValue(), json_encode($this->request)]));
    }

    public function toProcessQueryRequest(): ProcessQueryRequest
    {
        return new ProcessQueryRequest($this->getModel(), $this->url, $this->request);
    }
}
