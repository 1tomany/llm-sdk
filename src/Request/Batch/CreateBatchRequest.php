<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Trait\UsesModelTrait;
use OneToMany\LlmSdk\Request\Type\File\FileUri;

use function is_string;
use function trim;

class CreateBatchRequest
{
    use UsesModelTrait;

    /**
     * @var non-empty-string
     */
    private string $name;
    private ?FileUri $fileUri = null;

    public function __construct(
        string|Model $model,
        ?string $name,
        ?string $file = null,
    ) {
        $this
            ->usingModel($model)
            ->usingName($name)
            ->usingFileUri($file);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws InvalidArgumentException when the trimmed name is empty
     */
    public function usingName(?string $name): static
    {
        if (!$name = trim((string) $name)) {
            throw new InvalidArgumentException('The name cannot be empty.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * @throws InvalidArgumentException when the trimmed file URI is empty
     */
    public function usingFileUri(string|FileUri|null $fileUri): static
    {
        if (null === $fileUri) {
            $this->fileUri = null;
        } else {
            if (is_string($fileUri)) {
                if (!$fileUri = trim($fileUri)) {
                    throw new InvalidArgumentException('The file cannot be empty.');
                }

                $fileUri = new FileUri($fileUri, 'application/jsonl');
            }

            $this->fileUri = $fileUri;
        }

        return $this;
    }

    /**
     * @throws RuntimeException when the file URI has not been set
     */
    public function getFileUri(): FileUri
    {
        return $this->fileUri ?? throw new RuntimeException('The file URI has not been set.');
    }
}
