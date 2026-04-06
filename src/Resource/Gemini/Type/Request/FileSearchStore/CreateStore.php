<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\FileSearchStore;

final readonly class CreateStore
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {
    }

    /**
     * @return array{
     *   displayName: non-empty-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'displayName' => $this->name,
        ];
    }
}
