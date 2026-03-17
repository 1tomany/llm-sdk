<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;
use OneToMany\LlmSdk\Request\Type\File\FileUri;

use function is_string;
use function trim;

class CreateRequest extends BaseRequest
{
    /**
     * @var non-empty-string
     */
    private readonly string $name;
    private ?FileUri $file = null;

    /**
     * @param non-empty-string $name
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

    public function usingFile(string|FileUri|null $file): static
    {
        if (null === $file) {
            $this->file = null;
        } else {
            if (is_string($file)) {
                if (!$file = trim($file)) {
                    throw new InvalidArgumentException('The file cannot be empty.');
                }

                $file = new FileUri($file, 'application/jsonl');
            }

            $this->file = $file;
        }

        return $this;
    }

    public function getFile(): ?FileUri
    {
        return $this->file;
    }
}
