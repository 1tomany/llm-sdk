<?php

namespace OneToMany\LlmSdk\Contract\Client;

interface ClientInterface
{
    /**
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public static function getModels(): array;

    public function batches(): BatchClientInterface;

    public function files(): FileClientInterface;

    public function queries(): QueryClientInterface;
}
