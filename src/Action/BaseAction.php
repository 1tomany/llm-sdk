<?php

namespace OneToMany\LlmSdk\Action;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Factory\ClientFactory;

readonly class BaseAction
{
    public function __construct(protected ClientFactory $clientFactory)
    {
    }

    protected function createClient(Vendor $vendor): ClientInterface
    {
        return $this->clientFactory->create($vendor);
    }
}
