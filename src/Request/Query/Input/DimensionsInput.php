<?php

namespace OneToMany\LlmSdk\Request\Query\Input;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function is_object;

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
     * @throws ExceptionInvalidArgumentException when the output dimensions is not positive
     */
    public static function create(string|self $dimensions): self
    {
        if (is_object($dimensions)) {
            return $dimensions;
        }

        if ($dimensions < 1) {
            throw new InvalidArgumentException('The output dimensions must be positive.');
        }

        return new self($dimensions);
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }
}
