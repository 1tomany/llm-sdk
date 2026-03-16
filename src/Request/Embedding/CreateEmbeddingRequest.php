<?php

namespace OneToMany\LlmSdk\Request\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function sprintf;

class CreateEmbeddingRequest extends BaseRequest
{
    /**
     * @param positive-int $dimensions
     * @param array<string, mixed> $request
     *
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function __construct(
        string|Model|null $model,
        private int $dimensions,
        private array $request,
    ) {
        parent::__construct($model);

        if (!$this->getModel()->isEmbedding()) {
            throw new InvalidArgumentException(sprintf('The model "%s" is not an embedding model.', $this->getModel()->getValue()));
        }
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
