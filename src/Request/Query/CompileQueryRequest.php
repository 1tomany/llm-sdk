<?php

namespace OneToMany\LlmSdk\Request\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Type\Query\Dimensions;
use OneToMany\LlmSdk\Request\Type\Enum\Role;
use OneToMany\LlmSdk\Request\Type\File\FileUri;
use OneToMany\LlmSdk\Request\Type\Query\Prompt;
use OneToMany\LlmSdk\Request\Type\Query\Schema;

use function count;
use function is_string;
use function sprintf;
use function trim;

class CompileQueryRequest extends BaseRequest
{
    /**
     * @var list<FileUri>
     */
    private array $files = [];

    /**
     * @var list<Prompt>
     */
    private array $prompts = [];

    /**
     * A system prompt or instructions.
     */
    private ?Prompt $instructions = null;

    /**
     * Output schema for generative models that support structured outputs.
     */
    private ?Schema $schema = null;

    /**
     * Output dimensions for embedding models.
     */
    private ?Dimensions $dimensions = null;

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
     * @throws InvalidArgumentException when the model does not support system prompts
     */
    public function withPrompt(string|Prompt|null $prompt, Role $role = Role::User): static
    {
        if (is_string($prompt)) {
            $prompt = trim($prompt);
        }

        if (empty($prompt)) {
            return $this;
        }

        if (!$prompt instanceof Prompt) {
            $prompt = new Prompt($prompt, $role);
        }

        if ($this->getModel()->isEmbedding()) {
            if ($prompt->getRole()->isSystem()) {
                throw new InvalidArgumentException(sprintf('The model "%s" does not support system prompts.', $this->getModel()->getValue()));
            }

            $this->prompts = [$prompt];
        } else {
            if ($prompt->getRole()->isSystem()) {
                $this->instructions = $prompt;
            } else {
                $this->prompts[] = $prompt;
            }
        }

        return $this;
    }

    public function withUserPrompt(string|Prompt|null $prompt): static
    {
        return $this->withPrompt($prompt, Role::User);
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
        return $this->withPrompt($instructions, Role::System);
    }

    public function usingSystemPrompt(string|Prompt|null $systemPrompt): static
    {
        return $this->usingInstructions($systemPrompt);
    }

    public function getInstructions(): ?Prompt
    {
        return $this->instructions;
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

    public function hasComponents(): bool
    {
        return 0 !== count($this->files) || 0 !== count($this->prompts);
    }
}
