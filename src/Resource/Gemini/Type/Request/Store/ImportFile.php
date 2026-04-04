<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store;

use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Trait\FileNameTrait;

final readonly class ImportFile
{
    use FileNameTrait;

    /**
     * @var non-empty-string
     */
    public string $fileName;

    /**
     * @param non-empty-string $fileUri
     */
    public function __construct(
        ?string $fileUri,
    ) {
        $this->fileName = $this->generateFileName($fileUri);
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
