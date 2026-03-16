<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use function trim;

final readonly class BatchKeyInput
{
    /**
     * @param non-empty-string $key
     */
    public function __construct(
        private string $key,
    ) {
    }

    public static function create(string|self|null $batchKey): ?self
    {
        if (!$batchKey instanceof self) {
            if ($batchKey = trim($batchKey ?? '')) {
                $batchKey = new self($batchKey);
            }
        }

        return $batchKey ? $batchKey : null;
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
