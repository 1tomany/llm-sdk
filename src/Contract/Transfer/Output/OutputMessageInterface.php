<?php

namespace OneToMany\LlmSdk\Contract\Transfer\Output;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Transfer\Record\RecordInterface;

/**
 * @template R of RecordInterface
 */
interface OutputMessageInterface
{
    public Vendor $vendor { get; }

    /**
     * @var R
     */
    public RecordInterface $record { get; }

    /**
     * @return R
     */
    public function getRecord(): RecordInterface;
}
