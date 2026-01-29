<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Content;

use App\Prompt\Vendor\Model\Client\Gemini\Type\Content\Enum\FinishReason;

final readonly class Candidate
{
    /**
     * @param non-negative-int $index
     */
    public function __construct(
        public Content $content,
        public ?FinishReason $finishReason,
        public int $index = 0,
    ) {
    }
}
