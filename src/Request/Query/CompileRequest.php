<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\JsonSchemaInput;
use OneToMany\LlmSdk\Request\Query\Input\BatchKeyInput;
use OneToMany\LlmSdk\Request\Query\Input\DimensionsInput;
use OneToMany\LlmSdk\Request\Query\Input\FileInput;
use OneToMany\LlmSdk\Request\Query\Input\TextInput;

use function trim;

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
        if (!$batchKey instanceof BatchKeyInput) {
            if ($batchKey = trim($batchKey ?? '') ?: null) {
                $batchKey = new BatchKeyInput($batchKey);
            }

            $this->batchKey = $batchKey;
        }
    }

    public function getBatchKey(): ?BatchKeyInput
    {
        return $this->batchKey;
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
