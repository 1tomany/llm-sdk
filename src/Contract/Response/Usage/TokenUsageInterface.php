<?php

namespace OneToMany\LlmSdk\Contract\Response\Usage;

interface TokenUsageInterface
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

    /**
     * @return non-negative-int
     */
    public function getTotalTokens(): int;
}
