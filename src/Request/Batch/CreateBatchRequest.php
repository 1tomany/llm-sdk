<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Trait\UsesModelTrait;
use OneToMany\LlmSdk\Request\Type\File\FileUri;

use function trim;

class CreateBatchRequest
{
    use UsesModelTrait;

    /**
     * @var non-empty-string
     */
    private string $name;
    private FileUri $fileUri;

    public function __construct(
        string|Model $model,
        ?string $name,
        string|FileUri|null $fileUri,
    ) {
        $this
            ->usingModel($model)
            ->usingName($name)
            ->usingFileUri($fileUri);
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

    public function getFileUri(): FileUri
    {
        return $this->fileUri;
    }

    public function usingFileUri(string|FileUri|null $fileUri): static
    {
        if ($fileUri instanceof FileUri) {
            $fileUri = $fileUri->getUri();
        }

        $this->fileUri = FileUri::create($fileUri, 'application/json');

        return $this;
    }
}
