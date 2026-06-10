<?php

namespace OneToMany\LlmSdk\Transfer\Input\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Transfer\Input\InputMessageInterface;

final readonly class UploadFileInput implements InputMessageInterface
{
    /**
     * @param non-empty-string $path
     * @param non-empty-string $name
     * @param non-negative-int $size
     * @param non-empty-lowercase-string $format
     */
    public function __construct(
        public Vendor $vendor,
        public string $path,
        public string $name,
        public int $size,
        public string $format,
        public ?string $purpose = null,
    ) {
    }
}
