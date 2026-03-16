<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Request\Query\Input\Enum\Role;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Input\DimensionsInput;
use OneToMany\LlmSdk\Request\Query\Input\FileInput;
use OneToMany\LlmSdk\Request\Query\Input\SchemaInput;
use OneToMany\LlmSdk\Request\Query\Input\TextInput;

use function count;
use function is_string;
use function sprintf;
use function trim;

class CompileRequest extends BaseRequest
{
    private ?DimensionsInput $dimensions = null;
    private ?SchemaInput $schema = null;
    private ?TextInput $instructions = null;

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
    public function usingDimensions(int|DimensionsInput|null $dimensions): static
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
     * @param array<string, mixed>|SchemaInput|null $schema
     *
     * @throws InvalidArgumentException when the model does not support structured output
     */
    public function usingSchema(?string $name, array|SchemaInput|null $schema): static
    {
        if (null === $schema) {
            $this->schema = null;
        } else {
            if (!$this->getModel()->isGenerative()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support structured output.', $this->getModel()->getValue()));
            }

            $this->schema = SchemaInput::create($name, $schema);
        }

        return $this;
    }

    public function getSchema(): ?SchemaInput
    {
        return $this->schema;
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

    /**
     * @throws InvalidArgumentException when the model does not support system instructions
     */
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

        if ($this->getModel()->isEmbedding()) {
            if ($text->getRole()->isSystem()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support system instructions.', $this->getModel()->getValue()));
            }

            $this->textInputs = [$text];
        } else {
            if ($text->getRole()->isSystem()) {
                $this->instructions = $text;
            } else {
                $this->textInputs[] = $text;
            }
        }

        return $this;
    }

    /**
     * @return list<TextInput>
     */
    public function getTextInputs(): array
    {
        return $this->textInputs;
    }

    public function usingInstructions(string|TextInput|null $instructions): static
    {
        return $this->withText($instructions, Role::System);
    }

    public function getInstructions(): ?TextInput
    {
        return $this->instructions;
    }

    public function hasComponents(): bool
    {
        return 0 !== count($this->fileInputs) || 0 !== count($this->textInputs);
    }
}
