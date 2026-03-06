<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;

interface ClientInterface
{
    /**
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public static function getModels(): array;

    public function batches(): BatchClientInterface;

    public function files(): FilesResourceInterface;

    public function queries(): QueryClientInterface;
}
