<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;

interface ClientInterface
{
    public static function getVendor(): Vendor;

    public function batches(): BatchesResourceInterface;

    public function embeddings(): EmbeddingsResourceInterface;

    public function files(): FilesResourceInterface;

    public function queries(): QueriesResourceInterface;
}
