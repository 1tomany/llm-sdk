<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;

interface ClientInterface
{
    /**
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public static function getModels(): array;

    public function batches(): BatchesResourceInterface;

    public function files(): FilesResourceInterface;

    public function queries(): QueriesResourceInterface;
}
