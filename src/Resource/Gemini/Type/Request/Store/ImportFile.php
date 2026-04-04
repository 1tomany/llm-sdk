<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store;

final readonly class ImportFile
{
    /**
     * @param ?non-empty-string $fileName
     */
    public function __construct(
        public ?string $fileName,
    ) {
    }

    /**
     * @return array{
     *   fileName: ?non-empty-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'fileName' => $this->fileName,
        ];
    }
}
