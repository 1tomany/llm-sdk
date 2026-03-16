<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use function is_object;
use function max;
use function min;

final readonly class DimensionsInput
{
    /**
     * @param positive-int $dimensions
     */
    public function __construct(
        private int $dimensions,
    ) {
    }

    public static function create(string|self $dimensions): self
    {
        if (is_object($dimensions)) {
            return $dimensions;
        }

        return new self(min(max(1, $dimensions), 4096));
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }
}
