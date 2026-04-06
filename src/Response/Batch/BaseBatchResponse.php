<?php

namespace OneToMany\LlmSdk\Response\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\Trait\HasModelTrait;

readonly class BaseBatchResponse implements \JsonSerializable
{
    use HasModelTrait;

    /**
     * @param non-empty-string $uri
     * @param non-empty-string $status
     * @param ?non-empty-string $file
     */
    public function __construct(
        private Model $model,
        private string $uri,
        private string $status,
        private ?string $file = null,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return non-empty-string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   model: non-empty-lowercase-string,
     *   vendor: non-empty-lowercase-string,
     *   uri: non-empty-string,
     *   status: non-empty-string,
     *   file: ?non-empty-string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'model' => $this->getModel()->getValue(),
            'vendor' => $this->getVendor()->getValue(),
            'uri' => $this->getUri(),
            'status' => $this->getStatus(),
            'file' => $this->getFile(),
        ];
    }
}
