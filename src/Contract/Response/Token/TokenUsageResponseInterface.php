<?php

namespace OneToMany\AI\Contract\Response\Token;

interface TokenUsageResponseInterface
{
    /**
     * @return non-negative-int
     */
    public function getPromptTokens(): int;

    /**
     * @return non-negative-int
     */
    public function getOutputTokens(): int;
}
