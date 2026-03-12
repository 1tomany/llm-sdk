<?php

namespace OneToMany\LlmSdk\Request\Query\Component;

use OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;

use function max;

final readonly class DimensionsComponent implements ComponentInterface
{
    /**
     * @param positive-int $dimensions
     */
    public function __construct(private int $dimensions)
    {
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return max(1, $this->dimensions);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return Role::User;
    }
}
