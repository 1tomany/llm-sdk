<?php

namespace OneToMany\LlmSdk\Request\Type\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function is_object;
use function trim;

final readonly class BatchKey
{
    /**
     * @param non-empty-string $key
     */
    public function __construct(
        private string $key,
    ) {
    }

    /**
     * @throws InvalidArgumentException when the batch key is empty
     */
    public static function create(string|self $batchKey): self
    {
        if (is_object($batchKey)) {
            return $batchKey;
        }

        if (!$batchKey = trim($batchKey)) {
            throw new InvalidArgumentException('The batch key cannot be empty.');
        }

        return new self($batchKey);
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
