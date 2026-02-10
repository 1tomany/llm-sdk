<?php

namespace OneToMany\AI\Contract\Client\Type\Usage;

interface UsageInterface
{
    /**
     * @return non-negative-int
     */
    public function getInputTokens(): int;

    /**
     * @return non-negative-int
     */
    public function getCachedTokens(): int;

    /**
     * @return non-negative-int
     */
    public function getOutputTokens(): int;
}
