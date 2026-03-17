<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function parse_url;

use const PHP_URL_PATH;

final readonly class CreateBatch
{
    /**
     * @var non-empty-string
     */
    public string $fileName;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $fileUri
     */
    public function __construct(
        public string $name,
        string $fileUri,
    ) {
        if (!$fileName = parse_url($fileUri, PHP_URL_PATH)) {
            throw new InvalidArgumentException('The file URI cannot be empty.');
        }

        $this->fileName = $fileName;
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
