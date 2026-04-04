<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Request\Trait;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function array_key_last;
use function explode;
use function sprintf;
use function trim;

trait ExtractFileNameTrait
{
    /**
     * @return non-empty-string
     *
     * @throws InvalidArgumentException when a file name could not be extracted from the file URI
     */
    private function extractFileName(?string $fileUri): string
    {
        $fileUriBits = explode('/', trim((string) $fileUri));

        if (!$fileName = $fileUriBits[array_key_last($fileUriBits)]) {
            throw new InvalidArgumentException(sprintf('A file name could not be extracted from the file URI "%s".', $fileUri));
        }

        return sprintf('files/%s', $fileName);
    }
}
