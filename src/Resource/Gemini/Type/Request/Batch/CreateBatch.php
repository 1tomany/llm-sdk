<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Trait\ExtractFileNameTrait;

final readonly class CreateBatch
{
    use ExtractFileNameTrait;

    /**
     * @var non-empty-string
     */
    public string $fileName;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        ?string $fileUri,
    ) {
        $this->fileName = $this->extractFileNameFromFileUri($fileUri);
    }

    /**
     * @return array{
     *   batch: array{
     *     displayName: non-empty-string,
     *     inputConfig: array{
     *       fileName: non-empty-string,
     *     },
     *   },
     * }
     */
    public function toArray(): array
    {
        return [
            'batch' => [
                'displayName' => $this->name,
                'inputConfig' => [
                    'fileName' => $this->fileName,
                ],
            ],
        ];
    }
}
