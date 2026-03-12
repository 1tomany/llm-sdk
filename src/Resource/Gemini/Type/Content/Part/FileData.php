<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content\Part;

final readonly class FileData
{
    /**
     * @param non-empty-string $fileUri
     * @param non-empty-lowercase-string $mimeType
     */
    public function __construct(
        public string $fileUri,
        public string $mimeType,
    )
    {
    }

    /**
     * @return array{
     *   fileUri: non-empty-string,
     *   mimeType: non-empty-lowercase-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'fileUri' => $this->fileUri,
            'mimeType' => $this->mimeType,
        ];
    }
}
