<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

final readonly class ImportFile
{
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
        $fileUriBits = \explode('/', \trim((string) $fileUri));

        if (!$fileName = $fileUriBits[\array_key_last($fileUriBits)]) {
            throw new InvalidArgumentException('The file name could not be found from the URI.');
        }

        $this->fileName = $fileName;
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
