<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Component\JsonSchemaInput;
use OneToMany\LlmSdk\Request\Query\Input\DimensionsInput;
use OneToMany\LlmSdk\Request\Query\Input\FileInput;
use OneToMany\LlmSdk\Request\Query\Input\TextInput;

use function is_string;
use function sprintf;
use function trim;

class CompileRequest extends BaseRequest
{
    // private ?BatchKeyInput $batchKey = null;
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

    /**
     * @param ?non-empty-string $name
     *
     * @throws InvalidArgumentException when the model does not support structured output
     */
    public function usingJsonSchema(array|JsonSchemaInput|null $jsonSchema, ?string $name): static
    {
        if (null === $jsonSchema) {
            $this->jsonSchema = null;
        } else {
            if (!$this->getModel()->isGenerative()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support structured output.', $this->getModel()->getValue()));
            }

            $this->jsonSchema = JsonSchemaInput::create($jsonSchema, $name);
        }

        return $this;
    }

    public function getJsonSchema(): ?JsonSchemaInput
    {
        return $this->jsonSchema;
    }

    /**
     * @param ?non-empty-lowercase-string $format
     */
    public function withFile(string|FileInput|null $file, ?string $format): static
    {
        if (is_string($file)) {
            $file = trim($file);
        }

        if (!$file || !$format) {
            return $this;
        }

        if (!$file instanceof FileInput) {
            $file = new FileInput($file, $format);
        }

        $this->fileInputs[] = $file;

        return $this;
    }

    /**
     * @return list<FileInput>
     */
    public function getFileInputs(): array
    {
        return $this->fileInputs;
    }

    public function withText(string|TextInput|null $text, Role $role = Role::User): static
    {
        if (is_string($text)) {
            $text = trim($text);
        }

        if (empty($text)) {
            return $this;
        }

        if (!$text instanceof TextInput) {
            $text = new TextInput($text, $role);
        }

        $this->textInputs[] = $text;

        return $this;
    }

    /**
     * @return list<TextInput>
     */
    public function getTextInputs(): array
    {
        return $this->textInputs;
    }
}
