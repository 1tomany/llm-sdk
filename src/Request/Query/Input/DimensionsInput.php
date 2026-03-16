<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

final readonly class DimensionsInput
{
    /**
     * @param positive-int $dimensions
     */
    public function __construct(
        private int $dimensions,
    ) {
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }
}
