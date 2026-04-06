<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\FileSearchStore;

use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Trait\ExtractFileNameTrait;

final readonly class ImportFile
{
    use ExtractFileNameTrait;

    /**
     * @var non-empty-string
     */
    public string $fileName;

    /**
     * @param non-empty-string $fileUri
     */
    public function __construct(?string $fileUri)
    {
        $this->fileName = $this->extractFileNameFromFileUri($fileUri);
    }

    /**
     * @return array{
     *   fileName: non-empty-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'fileName' => $this->fileName,
        ];
    }
}
