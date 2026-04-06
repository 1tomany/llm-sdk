<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\SearchStoresResourceInterface;

interface ClientInterface
{
    public static function getVendor(): Vendor;

    public function batches(): BatchesResourceInterface;

    public function embeddings(): EmbeddingsResourceInterface;

    public function files(): FilesResourceInterface;

    public function outputs(): OutputsResourceInterface;

    public function queries(): QueriesResourceInterface;

    public function searchStores(): SearchStoresResourceInterface;
}
