<?php

namespace OneToMany\AI\Contract\Client\Type\Error;

interface ErrorTypeInterface
{
    public function getMessage(): string;

    /**
     * Prepares the message to be used in the message of
     * another exception by removing any trailing periods.
     */
    public function getInlineMessage(): string;
}
