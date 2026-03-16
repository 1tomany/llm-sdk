<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

final readonly class BatchKeyInput
{
    /**
     * @param non-empty-string $key
     */
    public function __construct(
        private string $key,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
