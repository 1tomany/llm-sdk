<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\File;

final readonly class UploadFile
{
    /**
     * @param ?non-empty-string $name
     */
    public function __construct(
        public ?string $name,
    ) {
    }

    /**
     * @return array{
     *   file: array{
     *     displayName: ?non-empty-string,
     *   },
     * }
     */
    public function toArray(): array
    {
        return [
            'file' => [
                'displayName' => $this->name,
            ],
        ];
    }
}
