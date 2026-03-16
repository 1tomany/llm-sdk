<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\JsonSchemaInput;
use OneToMany\LlmSdk\Request\Query\Input\BatchKeyInput;
use OneToMany\LlmSdk\Request\Query\Input\DimensionsInput;
use OneToMany\LlmSdk\Request\Query\Input\FileInput;
use OneToMany\LlmSdk\Request\Query\Input\TextInput;

use function sprintf;

class CompileRequest extends BaseRequest
{
    private ?BatchKeyInput $batchKey = null;
    private ?DimensionsInput $dimensions = null;
    private ?JsonSchemaInput $jsonSchema = null;

    /**
     * @var list<FileInput>
     */
    private array $fileInputs = [];

    /**
     * @var list<TextInput>
     */
    private array $textInputs = [];

    public function usingBatchKey(string|BatchKeyInput|null $batchKey): static
    {
        if (null === $batchKey) {
            $this->batchKey = null;
        } else {
            $this->batchKey = BatchKeyInput::create($batchKey);
        }

        return $this;
    }

    public function getBatchKey(): ?BatchKeyInput
    {
        return $this->batchKey;
    }

    /**
     * @throws InvalidArgumentException when the model does not support changing the output dimensions
     */
    public function usingDimensions(string|DimensionsInput|null $dimensions): static
    {
        if (null === $dimensions) {
            $this->dimensions = null;
        } else {
            if (!$this->getModel()->isEmbedding()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support changing the output dimensions.', $this->getModel()->getValue()));
            }

            $this->dimensions = DimensionsInput::create($dimensions);
        }

        return $this;
    }

    public function getDimensions(): ?DimensionsInput
    {
        return $this->dimensions;
    }

    public function getJsonSchema(): ?JsonSchemaInput
    {
        return $this->jsonSchema;
    }
}
