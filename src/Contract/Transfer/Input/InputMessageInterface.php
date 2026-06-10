<?php

namespace OneToMany\LlmSdk\Contract\Transfer\Input;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

interface InputMessageInterface
{
    public Vendor $vendor { get; }
}
