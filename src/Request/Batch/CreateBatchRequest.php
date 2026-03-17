<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Type\File\FileUri;

use function is_string;
use function trim;

class CreateBatchRequest extends BaseRequest
{
    /**
     * @var non-empty-string
     */
    private readonly string $name;
    private ?FileUri $fileUri = null;

    /**
     * @param non-empty-string $name
     *
     * @throws InvalidArgumentException when the trimmed name is empty
     */
    public function __construct(
        string|Model|null $model,
        string $name,
        ?string $file = null,
    ) {
        parent::__construct($model);

        if (!$name = trim($name)) {
            throw new InvalidArgumentException('The name cannot be empty.');
        }

        $this->name = $name;
        $this->usingFile($file);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws InvalidArgumentException when the trimmed file is empty
     */
    public function usingFile(string|FileUri|null $fileUri): static
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
     * @throws RuntimeException when the file is missing
     */
    public function getFileUri(): FileUri
    {
        return $this->fileUri ?? throw new RuntimeException('The file is missing.');
    }
}
