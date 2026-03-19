<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function basename;
use function parse_url;
use function sprintf;
use function trim;

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
        if (!$urlPath = parse_url($fileUri, PHP_URL_PATH)) {
            throw new InvalidArgumentException(sprintf('The file URI "%s" does not contain a path component.', $fileUri));
        }

        if (!$fileId = trim(basename($urlPath))) {
            throw new InvalidArgumentException(sprintf('The path "%s" does not contain a file ID.', $urlPath));
        }

        $this->fileName = sprintf('files/%s', $fileId);
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
