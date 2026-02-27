<?php

namespace OneToMany\LlmSdk\Contract\Client\Type\Usage;

use OneToMany\LlmSdk\Response\Query\UsageResponse;

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

    public function toResponse(): UsageResponse;
}
