<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Query\Type\Dimensions;
use OneToMany\LlmSdk\Request\Query\Type\Enum\Role;
use OneToMany\LlmSdk\Request\Query\Type\FileUri;
use OneToMany\LlmSdk\Request\Query\Type\Schema;
use OneToMany\LlmSdk\Request\Query\Type\Prompt;

use function count;
use function is_string;
use function sprintf;
use function trim;

class CompileQueryRequest extends BaseRequest
{
    private ?Dimensions $dimensions = null;
    private ?Schema $schema = null;
    private ?Prompt $instructions = null;

    /**
     * @var list<FileUri>
     */
    private array $files = [];

    /**
     * @var list<Prompt>
     */
    private array $prompts = [];

    /**
     * @throws InvalidArgumentException when the model does not support changing the output dimensions
     */
    public function usingDimensions(int|Dimensions|null $dimensions): static
    {
        if (null === $dimensions) {
            $this->dimensions = null;
        } else {
            if (!$this->getModel()->isEmbedding()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support changing the output dimensions.', $this->getModel()->getValue()));
            }

            $this->dimensions = Dimensions::create($dimensions);
        }

        return $this;
    }

    public function getDimensions(): ?Dimensions
    {
        return $this->dimensions;
    }

    /**
     * @param ?non-empty-string $name
     * @param array<string, mixed>|Schema|null $schema
     *
     * @throws InvalidArgumentException when the model does not support structured output
     */
    public function usingSchema(?string $name, array|Schema|null $schema): static
    {
        if (null === $schema) {
            $this->schema = null;
        } else {
            if (!$this->getModel()->isGenerative()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support structured output.', $this->getModel()->getValue()));
            }

            $this->schema = Schema::create($name, $schema);
        }

        return $this;
    }

    public function getSchema(): ?Schema
    {
        return $this->schema;
    }

    /**
     * @param ?non-empty-lowercase-string $format
     */
    public function withFile(string|FileUri|null $file, ?string $format): static
    {
        if (is_string($file)) {
            $file = trim($file);
        }

        if (!$file || !$format) {
            return $this;
        }

        if (!$file instanceof FileUri) {
            $file = new FileUri($file, $format);
        }

        $this->files[] = $file;

        return $this;
    }

    /**
     * @return list<FileUri>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @throws InvalidArgumentException when the model does not support system instructions
     */
    public function withText(string|Prompt|null $text, Role $role = Role::User): static
    {
        if (is_string($text)) {
            $text = trim($text);
        }

        if (empty($text)) {
            return $this;
        }

        if (!$text instanceof Prompt) {
            $text = new Prompt($text, $role);
        }

        if ($this->getModel()->isEmbedding()) {
            if ($text->getRole()->isSystem()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support system instructions.', $this->getModel()->getValue()));
            }

            $this->prompts = [$text];
        } else {
            if ($text->getRole()->isSystem()) {
                $this->instructions = $text;
            } else {
                $this->prompts[] = $text;
            }
        }

        return $this;
    }

    /**
     * @return list<Prompt>
     */
    public function getPrompts(): array
    {
        return $this->prompts;
    }

    public function usingInstructions(string|Prompt|null $instructions): static
    {
        return $this->withText($instructions, Role::System);
    }

    public function getInstructions(): ?Prompt
    {
        return $this->instructions;
    }

    public function hasComponents(): bool
    {
        return 0 !== count($this->files) || 0 !== count($this->prompts);
    }
}
