<?php

namespace OneToMany\LlmSdk\Transfer\Output\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Transfer\Output\OutputMessageInterface;
use OneToMany\LlmSdk\Contract\Transfer\Record\RecordInterface;
use OneToMany\LlmSdk\Transfer\Record\File\FileRecord;

/**
 * @implements OutputMessageInterface<FileRecord>
 */
final readonly class FileOutput implements OutputMessageInterface
{
    public function __construct(
        public Vendor $vendor,
        public FileRecord $record,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Transfer\Output\OutputMessageInterface
     */
    public function __invoke(): RecordInterface
    {
        return $this->record;
    }
}
