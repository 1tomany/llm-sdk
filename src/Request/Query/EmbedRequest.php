<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

class EmbedRequest extends CompileRequest
{
    /**
     * @var ?positive-int
     */
    private ?int $dimensions = null;

    /**
     * @throws InvalidArgumentException when the model is not an embedding model
     * @throws InvalidArgumentException when the dimensions are not a positive integer
     */
    public function withDimensions(int $dimensions): static
    {
        if (!$this->getModel()->isEmbedding()) {
            throw new InvalidArgumentException('Output dimensions can only be added to a query using an embedding model.');
        }

        if ($dimensions < 1) {
            throw new InvalidArgumentException('Output dimensions must be a positive integer.');
        }

        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return ?positive-int
     */
    public function getDimensions(): ?int
    {
        return $this->dimensions;
    }
}
